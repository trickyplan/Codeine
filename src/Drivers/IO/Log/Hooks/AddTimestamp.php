<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Log.Spit.Channel.Before', function ($Call)
    {
        if (F::Dot($Call, 'Storages.'.$Call['Channel'].'.Log.AddTimestamp'))
            F::Log(date(DATE_W3C, Started), LOG_NOTICE, $Call['Channel'], false, true);

        return $Call;
    });