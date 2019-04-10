<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $Delay = mt_rand(0,F::Dot($Call, 'Security.Timing.Delay.Maximum'));
        F::Log('Delayed for *'.$Delay.'* microseconds', LOG_INFO, 'Security');
        usleep($Delay);
        return $Call;
    });