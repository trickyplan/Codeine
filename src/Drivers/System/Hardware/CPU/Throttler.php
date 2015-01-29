<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        if (sys_getloadavg()[0] > $Call['Throttler']['CPU Limit'])
        {
            F::Run('System.Sleep', 'Do', ['Seconds' => $Call['Throttler']['Seconds']]);
            F::Log('Throttled on '.$Call['Throttler']['Seconds'].' seconds', LOG_INFO);
        }

        return $Call;
    });