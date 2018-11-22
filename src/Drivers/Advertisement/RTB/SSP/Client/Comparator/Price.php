<?php
    
    setFn('Do', function ($Call)
    {
        $Call = F::Hook('RTB.SSP.Client.Comparator.Price.Started', $Call);

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
        
                    foreach ($Seat['bid'] as &$Bid)
                    {
                        $Bid['price'] = ($Currency == 'USD')
                            ? $Bid['price']
                            : F::Dot($Call, 'Finance.Currency.'.$Currency.'.USD') * $Bid['price'];
                        // $Bid['price']   /= 1000; // CPM
                        
                        if (F::Dot($Call, 'RTB.Winner.Price') <= $Bid['price'])
                        {

                            $Call = F::Dot($Call, 'RTB.Winner.Bid', $Bid);
                            $Call = F::Dot($Call, 'RTB.Winner.NativeCurrency', $Currency);
                            $Call = F::Dot($Call, 'RTB.Winner.Currency', 'USD');
                            $Call = F::Dot($Call, 'RTB.Winner.Price', $Bid['price']);
                            $Call = F::Dot($Call, 'RTB.Winner.DSP', F::Dot($Call, 'RTB.DSP.Items.'.$ResultFrom));
                            
                        }
        
                        F::Log(function () use ($Bid) {return 'Bid is compared '.j($Bid);} , LOG_INFO, 'RTB');
                    }
                }
            }
        }
        
        $Call = F::Hook('RTB.SSP.Client.Comparator.Price.Finished', $Call);
    
        return $Call;
    });