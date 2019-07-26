<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Log.Spit.Channel.Before', function ($Call)
    {
        if (F::Dot($Call, 'Storages.'.$Call['Channel'].'.Log.ConvertTimestampsToAbsolute'))
            foreach ($Call['Channel Logs'] as $Channel => &$Row)
                $Row['T'] = date(DATE_W3C, floor(Started)).'(+'.$Row['T'].')';
            
        return $Call;
    });