<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Log.Spit.Channel.Before', function ($Call)
    {
        foreach ($Call['Channel Logs'] as &$Row)
            $Row['Z'] = is_scalar($Row['X']);
        
        return $Call;
    });