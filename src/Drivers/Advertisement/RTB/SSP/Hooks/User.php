<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $ID = F::Dot($Call, 'Request.VID') ?: REQID;

        return F::Dot($Call, 'RTB.DSP.Items', array_map(function ($DSP) use ($ID) {
            $DSP['Request']['user']['id'] = $ID;
            return $DSP;
        }, F::Dot($Call, 'RTB.DSP.Items')));
    });