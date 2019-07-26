<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Log.Spit.Channel.Before', function ($Call)
    {
        if (F::Dot($Call, 'Storages.'.$Call['Channel'].'.RemoveNewlines'))
            foreach ($Call['Channel Logs'] as &$Row)
                $Row['X'] = strtr($Row['X'], [PHP_EOL => '/']);
        
        return $Call;
    });