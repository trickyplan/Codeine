<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeLogSpit', function ($Call)
    {
        foreach ($Call['Channel Logs'] as &$Row)
            $Row['T'] = date(DATE_W3C, floor(Started)).'(+'.$Row['T'].')';
            
        return $Call;
    });