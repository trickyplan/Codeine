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
        
        foreach ($DSPs as $Name => $DSP)
        {
            $DSP['Request'] = F::Merge($DSP['Request'], $Call['RTB']['Request']);
            $DSP['Request']['imp'][0] = $DSP['Impression'];
            $DSP['Request']['imp'][0]['id'] = F::Dot($Call, 'RTB.Impression.ID');
            $DSP['Request']['imp'][0]['banner'] = $DSP['Banner'];
            
            $MultiRequest['Where']['ID'][$Name] = $DSP['Endpoint'];
            $MultiRequest['Data'][$Name] = j($DSP['Request']);
            $MultiRequest['CURL']['Headers']['X-OpenRTB-Version:'] = $DSP['Version'];

            F::Log(function () use ($DSP) {return 'Request to '.$DSP['Endpoint'].': '.j($DSP['Request']);}, LOG_INFO, 'RTB');
            
            $Call = F::Hook('RTB.SSP.Request.Created', $Call); // New Hook Convention
        }
        
        $Call = F::Dot($Call, 'RTB.Result', F::Run('IO', 'Write', [
            'Storage'          => 'Web',
            'Output Format'    => 'Formats.JSON',
            'CURL'             =>
            [
                'Headers'  =>
                [
                    'Content-Type: application/json'
                ]
            ],
        ], $MultiRequest));
        
        $Call = F::Hook('RTB.SSP.Request.Executed', $Call); // New Hook Convention
        //$Call = F::Hook('afterRTBRequest', $Call); // Old Convention
        
        return $Call;
    });