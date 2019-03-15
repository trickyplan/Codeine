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
            $Row['X'] = strtr($Row['X'], [PHP_EOL => '/']);
        
        return $Call;
    });