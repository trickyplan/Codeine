<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('RTB.SSP.Request.Executed', function ($Call)
    {
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