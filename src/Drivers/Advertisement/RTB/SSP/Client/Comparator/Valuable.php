<?php
    
    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeSSPClientComparatorValuable', $Call);
    
        $Results = F::Dot($Call, 'RTB.DSP.Result');
    
        $WinnerRURPrice = 0;
        $WinnerBid = [];
        $Searches = ['${AUCTION_BID_ID}', '${AUCTION_IMP_ID}', '${AUCTION_PRICE}', '${AUCTION_CURRENCY}'];
        foreach ($Results as $cIndex => $cResult) {
            if (empty($cResult)) {
               F::Log('Empty RTB response (' . $cIndex . ')', LOG_NOTICE, 'RTB');
               continue;
            }
    
            if (empty($cResult['seatbid'])) {
                F::Log('No seatbid section in RTB response', LOG_NOTICE, 'RTB');
                continue;
            }
    
            $Currency = isset($cResult['cur'])? $cResult['cur']: 'USD';
            foreach ($cResult['seatbid'] as $SeatID => $Seat)
            {
                F::Log('*'.count($Seat).'* bids loaded', LOG_INFO, 'RTB');
    
                foreach ($Seat['bid'] as $Bid)
                {
                    $RURPrice = $Currency == 'RUB'
                        ? $Bid['price']
                        : F::Dot($Call, 'Finance.Currency.'.$Currency.'.RUR.Rate') * $Bid['price'];
                    
                    if ($WinnerRURPrice < $RURPrice) {
                        $Bid['nurl'] = str_replace('${AUCTION_PRICE}', $Bid['price'], $Bid['nurl']);
                        $Bid['adm'] = str_replace($Searches, [$Bid['id'], $Bid['impid'], $Bid['price'], $Currency ], $Bid['adm']);
                        $Bid['price'] /= 1000; // CPM
                        $WinnerBid = $Bid;
                        $WinnerRURPrice = $RURPrice;
                    }
    
                    F::Log('Bid proccesed '.j($Bid), LOG_INFO, 'RTB');
                }
            }
        }
    
        $Call = F::Dot($Call, 'RTB.Result.Seats', [$WinnerBid]);
        $Call = F::Hook('afterSSPClientComparatorValuable', $Call);
    
        return $Call;
    });