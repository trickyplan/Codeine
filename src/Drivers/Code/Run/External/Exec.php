<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Run', function ($Call)
    {
        F::Log('Will execute command: '.$Call['Command'], LOG_INFO);
        
            $Call = F::Hook('beforeExecRun', $Call);
        
                exec($Call['Command'], $Call['Run']['Result'], $Call['Run']['Code']);
        
            $Call = F::Hook('afterExecRun', $Call);
        
        $Call['Run']['Result'] = implode(PHP_EOL, $Call['Run']['Result']);
        
        if (empty($Call['Run']['Result']))
            F::Log('No output', LOG_INFO);
        else
            F::Log('Output: *'.mb_substr($Call['Run']['Result'], 0, $Call['Exec']['Cut']).'*', LOG_INFO);
        
        F::Log('Return code: *'.$Call['Run']['Code'].'*', ($Call['Run']['Code'] > 0)? LOG_NOTICE: LOG_INFO);
        
        return $Call['Run'];
    });