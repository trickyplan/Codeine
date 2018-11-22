<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Convert price', function ($Call) {
        if (F::Dot($Call, 'RTB.Winner.NativeCurrency') != F::Dot($Call, 'RTB.Winner.Currency')) {
            $Price = F::Run('Finance.Currency', 'Rate.Convert', 
            [
                'Value' => F::Dot($Call, 'RTB.Winner.Price'),
                'From' => F::Dot($Call, 'RTB.Winner.Currency'),
                'To' => F::Dot($Call, 'RTB.Winner.NativeCurrency')
            ]);

            $Call = F::CopyDot($Call, 'RTB.Winner.NativeCurrency', 'RTB.Winner.Currency');
            $Call = F::Dot($Call, 'RTB.Winner.Bid.price', $Price);
        }

        return $Call;
    });
    
    setFn('RTB.SSP.Request.Executed', function ($Call)
    {
        $Call = F::Run(null, 'Convert price', $Call);

        $Map =
        [
            '${AUCTION_BID_ID}' => F::Dot($Call, 'RTB.Winner.Bid.id'),
            '${AUCTION_IMP_ID}' => F::Dot($Call, 'RTB.Winner.Bid.impid'),
            '${AUCTION_PRICE}'  => F::Dot($Call, 'RTB.Winner.Bid.price'),
            '${AUCTION_CURRENCY}'   => F::Dot($Call, 'RTB.Winner.Currency')
        ];
       
        $Call = F::Dot($Call, 'RTB.Winner.Bid.nurl', strtr(F::Dot($Call, 'RTB.Winner.Bid.nurl'), $Map));
        $Call = F::Dot($Call, 'RTB.Winner.Bid.adm', strtr(F::Dot($Call, 'RTB.Winner.Bid.adm'), $Map));

        return $Call;
    });