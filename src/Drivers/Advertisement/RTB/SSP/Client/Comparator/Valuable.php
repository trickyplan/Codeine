<?php
    
    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeSSPClientComparatorValuable', $Call);
        $Results = F::Dot($Call, 'RTB.Result');
    
        $Searches = ['${AUCTION_BID_ID}', '${AUCTION_IMP_ID}', '${AUCTION_PRICE}', '${AUCTION_CURRENCY}'];
        F::Dot($Call, 'RTB.Winner.Price', 0);
        
        if (empty($Results))
            ;
        else
        {
            foreach ($Results as $ResultFrom => $cResult)
            {
                if (empty($cResult))
                {
                   F::Log('Empty RTB response (' . $ResultFrom . ')', LOG_NOTICE, 'RTB');
                   continue;
                }
        
                if (empty($cResult['seatbid']))
                {
                    F::Log('No seatbid section in RTB response', LOG_NOTICE, 'RTB');
                    continue;
                }
        
                $Currency = isset($cResult['cur'])? $cResult['cur']: 'USD';
                
                foreach ($cResult['seatbid'] as $SeatID => $Seat)
                {
                    F::Log('*'.count($Seat).'* bids loaded', LOG_INFO, 'RTB');
        
                    foreach ($Seat['bid'] as $Bid)
                    {
                        $RURPrice = ($Currency == 'RUB')
                            ? $Bid['price']
                            : F::Dot($Call, 'Finance.Currency.'.$Currency.'.RUR') * $Bid['price'];

                        if (F::Dot($Call, 'RTB.Winner.Price') <= $RURPrice)
                        {
                            $Bid['nurl']    = str_replace('${AUCTION_PRICE}', $Bid['price'], $Bid['nurl']);
                            $Bid['adm']     = str_replace($Searches, [$Bid['id'], $Bid['impid'], $Bid['price'], $Currency ], $Bid['adm']);
                            $Bid['price']   /= 1000; // CPM

                            $Call = F::Dot($Call, 'RTB.Winner.Bid', $Bid);
                            $Call = F::Dot($Call, 'RTB.Winner.Price', $RURPrice);
                            $Call = F::Dot($Call, 'RTB.Winner.DSP', $ResultFrom);
                        }
        
                        F::Log('Bid is compared '.j($Bid), LOG_INFO, 'RTB');
                    }
                }
            }
        }
    
        $Call = F::Hook('afterSSPClientComparatorValuable', $Call);
    
        return $Call;
    });