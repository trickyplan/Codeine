<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeRTBRequest', function ($Call)
    {
        $ID = F::Dot($Call, 'Request.VID') ? F::Dot($Call, 'Request.VID') : F::Dot($Call, 'SID');
        $Call = F::Dot($Call, 'RTB.Request.user.id', $ID);

        return $Call;
    });