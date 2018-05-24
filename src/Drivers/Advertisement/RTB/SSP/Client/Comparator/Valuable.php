<?php
    
    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeSSPClientComparatorValuable', $Call);
        $Results = F::Dot($Call, 'RTB.Result');
    
        F::Dot($Call, 'RTB.Winner.Price', 0);
        
        if (empty($Results))
            ;
        else
        {
            foreach ($Results as $ResultFrom => $cResult)
            {
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
                            $Bid['price']   /= 1000; // CPM

                            $Call = F::Dot($Call, 'RTB.Winner.Bid', $Bid);
                            $Call = F::Dot($Call, 'RTB.Winner.Currency', $Currency);
                            $Call = F::Dot($Call, 'RTB.Winner.Price', $RURPrice);
                            $Call = F::Dot($Call, 'RTB.Winner.DSP', F::Dot($Call, 'RTB.DSP.Items.'.$ResultFrom));
                            
                        }
        
                        F::Log('Bid is compared '.j($Bid), LOG_INFO, 'RTB');
                    }
                }
            }
        }
    
        $Call = F::Hook('afterSSPClientComparatorValuable', $Call);
    
        return $Call;
    });