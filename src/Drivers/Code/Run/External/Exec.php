<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Run', function ($Call)
    {
        exec($Call['Command'], $Call['Run']['Result'], $Call['Run']['Code']);
        
        $Call['Run']['Result'] = implode(PHP_EOL, $Call['Run']['Result']);
        
        F::Log('Command: '.$Call['Command'], LOG_INFO);
        
        if (empty($Call['Run']['Result']))
            F::Log('Zero output', LOG_INFO);
        else
            F::Log('Output: '.$Call['Run']['Result'], LOG_INFO);
        
        if ($Call['Run']['Code'] > 0)
            $Verbose = LOG_WARNING;
        else
            $Verbose = LOG_INFO;
        
        F::Log('Return Code:'.$Call['Run']['Code'], $Verbose);
        
        return $Call['Run'];
    });