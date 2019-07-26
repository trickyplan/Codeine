<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Log.Spit.Channel.Before', function ($Call)
    {
        if (F::Dot($Call, 'Storages.'.$Call['Channel'].'.Sort'))
            $Call['Channel Logs'] = F::Sort($Call['Channel Logs'], 0);
        
        return $Call;
    });