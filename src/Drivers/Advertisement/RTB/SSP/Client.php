<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Apply (null, 'Make Request', $Call);

        return $Call;
    });
    
    setFn('Make Request', function ($Call)
    {
        F::Log('DSP active: *'.F::Dot($Call, 'RTB.DSP.Name').'*', LOG_INFO, 'RTB');
    
        $Call = F::Dot($Call, 'RTB.DSP.Request',
            F::Live(
                F::Dot($Call, 'RTB.DSP.Request')
            )
        );

        $Call = F::Hook('beforeRTBRequest', $Call);

        $DSPs = F::Dot($Call, 'RTB.DSP.Items');
        
        $MultiRequest = [];

        if (empty($DSPs))
            $Results = null ;
        else
        {
            foreach ($DSPs as $Name => $Call['DSP'])
            {
                if ($Call['DSP'] == null)
                    continue;

                $Call['DSP']['Request'] = F::Merge($Call['DSP']['Request'], $Call['RTB']['Request']);
                $Call['DSP']['Request']['imp'][0] = $Call['DSP']['Impression'];
                $Call['DSP']['Request']['imp'][0]['id'] = F::Dot($Call, 'RTB.Impression.ID');
                $Call['DSP']['Request']['imp'][0]['banner'] = F::Dot($Call['DSP'], 'Banner');

                $MultiRequest['Where']['ID'][$Name] = $Call['DSP']['Endpoint'];
                $MultiRequest['Data'][$Name] = j($Call['DSP']['Request']);

                $MultiRequest['CURL']['Headers']['X-OpenRTB-Version:'] = $Call['DSP']['Version'];

                F::Log(function () use ($Call) {return 'Request to '.$Call['DSP']['Endpoint'].': '.j($Call['DSP']['Request']);}, LOG_INFO, 'RTB');
                $Call = F::Hook('RTB.SSP.Request.Created', $Call); // New Hook Convention
            }

            if (empty($MultiRequest))
                $Results = null;
            else
            {
                $RTBResult =  F::Run('IO', 'Write', $Call, [
                    'Storage'          => 'Web',
                    'Output Format'    => 'Formats.JSON',
                    'CURL'             =>
                        [
                            'Headers'  =>
                                [
                                    'Content-Type: application/json'
                                ]
                        ],
                ], $MultiRequest);

                $Call = F::Dot($Call, 'RTB.Result', $RTBResult);

                foreach ($MultiRequest['Where']['ID'] as $IX => $DSP) {
                    $RequestData = json_decode($MultiRequest['Data'][$IX]);
                    $Call['RTB']['Debug'][$DSP]['Request'] = $RequestData;
                    $Call['RTB']['Debug'][$DSP]['CURL'] = 'curl -H "Content-Type: application/json" -H "x-openrtb-version: 2.0" -d '
                        . '\'' . $MultiRequest['Data'][$IX] . '\' ' . $DSP;
                }

                $Results = F::Dot($Call, 'RTB.Result');
            }
        }
        
        if (empty($Results))
            ;
        else
            foreach ($Results as $DSP => $Result)
                $Call['RTB']['Debug'][$DSP]['Response'] = $Result;
        
        $Call = F::Hook('RTB.SSP.RequestGroup.Executed', $Call); // New Hook Convention
        
        if (F::Dot($Call, 'RTB.Winner.Bid') === null)
            $Call = F::Hook('RTB.SSP.Winner.None', $Call);
        else
            $Call = F::Hook('RTB.SSP.Winner.Exists', $Call);

        return $Call;
    });