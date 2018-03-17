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
        $Result = F::Run (null, 'Select Bid', $Call);

        return $Result;
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
        
            $Call = F::Dot($Call, 'RTB.DSP.Result', F::Run('IO', 'Write',
                     [
                         'Storage'          => 'Web',
                         'Format'           => 'Formats.JSON',
                         'Output Format'    => 'Formats.JSON',
                         'CURL'             =>
                         [
                             'Headers'  =>
                             [
                                 'Content-Type: application/json',
                                 'x-openrtb-version: '.F::Dot($Call, 'RTB.Client.Version')
                             ]
                         ],
                         'Where'    => F::Dot($Call, 'RTB.DSP.Endpoint').'&dc='.time().uniqid('',true),
                         'Data'     => F::Dot($Call, 'RTB.DSP.Request')
                     ]));

        $Call = F::Hook('afterRTBRequest', $Call);
        
        F::Log(function () use ($Call) {return 'Request: '.j(F::Dot($Call, 'RTB.DSP.Request'));}, LOG_INFO, 'RTB');
        
        if (F::Dot($Call, 'RTB.DSP.Debug.LogEmptyResponse') == true)
            if (F::Dot($Call, 'RTB.DSP.Result') == [null])
                F::Log(function () use ($Call) {return 'Zero Response: '.j(F::Dot($Call, 'RTB.DSP.Request'));} , LOG_WARNING, 'RTB');

        return $Call;
    });

    setFn('Select Bid', function ($Call)
    {
        $Result = null;
        
        if (($Result = F::Dot($Call, 'RTB.DSP.Result')) === null)
            F::Log('Empty RTB response', LOG_NOTICE, 'RTB');
        else
            foreach ($Result as $cResult)
            {
                if (empty($cResult['seatbid']))
                    F::Log('No seatbid section in RTB response', LOG_NOTICE, 'RTB');
                else
                {
                    F::Log('*'.count($cResult['seatbid']).'* seatbids loaded', LOG_INFO, 'RTB');
                    // Массив для замен макросов. Добавлены макросы для которых поля  required или имеют default.
                    $Searches = ['${AUCTION_BID_ID}', '${AUCTION_IMP_ID}', '${AUCTION_PRICE}', '${AUCTION_CURRENCY}'];
                    // Не включенные макросы.
                    // '${AUCTION_ID}', '${AUCTION_SEAT_ID}', '${AUCTION_AD_ID}'
                    
                    foreach ($cResult['seatbid'] as $SeatID => $Seat)
                    {
                        F::Log('*'.count($Seat).'* bids loaded', LOG_INFO, 'RTB');
                        
                        foreach ($Seat['bid'] as $Bid)
                        {
                            F::Run('IO', 'Read',
                                [
                                    'Storage'   => 'Web',
                                    'Time'      => uniqid(true),
                                    'Where'     => str_replace('${AUCTION_PRICE}', $Bid['price'], $Bid['nurl'])
                                ]);
                            
                            F::Log('Bid processed '.j($Bid), LOG_INFO, 'RTB');
                            $Currency = isset($cResult['cur'])? $cResult['cur']: 'USD';
                            $Bid['adm'] = str_replace($Searches, [$Bid['id'], $Bid['impid'], $Bid['price'], $Currency ], $Bid['adm']);
                            $Call = F::Dot($Call, 'RTB.Result.Seats.'.$SeatID, $Bid);
                        }
                    }
                }
            }
            
        return F::Dot($Call, 'RTB.Result.Seats.0.adm'); // ?
    });