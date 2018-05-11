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

        return F::Dot($Call, 'RTB.Result.Seats');
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

        $DSPItems = F::Dot($Call, 'RTB.DSP.Items');
        $RequestData = [];
        foreach ($DSPItems as $Item) {
            $UniqID = uniqid('',true);
            $RequestData['Where']['ID'][$UniqID] = $Item['Endpoint'].'?dc='.time().$UniqID;
            $RequestData['Data'][$UniqID] = $Item['Request'];
            $RequestData['CURL']['Headers']['X-OpenRTB-Version:'] = $Item['Version'];
        }

        $Call = F::Dot($Call, 'RTB.DSP.Result', F::Run('IO', 'Write', [
            'Storage'          => 'Web',
            'Output Format'    => 'Formats.JSON',
            'CURL'             =>
            [
                'Headers'  =>
                [
                    'Content-Type: application/json'
                ]
            ],
        ], $RequestData));

        $Call = F::Hook('RTB.SSP.Request.Created', $Call); // New Hook Convention
        $Call = F::Hook('afterRTBRequest', $Call); // Old Convention
        
        F::Log(function () use ($Call) {return 'Request: '.j(F::Dot($Call, 'RTB.DSP.Request'));}, LOG_INFO, 'RTB');
        
        if (F::Dot($Call, 'RTB.DSP.Debug.LogEmptyResponse') == true)
            if (F::Dot($Call, 'RTB.DSP.Result') == [null])
                F::Log(function () use ($Call) {return 'Zero Response: '.j(F::Dot($Call, 'RTB.DSP.Request'));} , LOG_WARNING, 'RTB');
        return $Call;
    });