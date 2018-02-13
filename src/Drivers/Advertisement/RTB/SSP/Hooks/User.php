<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if ($ID = F::Dot($Call, 'Request.VID') === null)
            $ID = REQID;
        $Call = F::Dot($Call, 'RTB.DSP.Request.user.id', $ID);
        return $Call;
    });