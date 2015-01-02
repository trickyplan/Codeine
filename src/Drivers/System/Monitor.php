<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        foreach ($Call['Sensors'] as $Sensor)
            $Sensors[$Sensor] = F::Run(null, $Sensor, $Call);

        return $Sensors;
    });

    setFn('LA', function ($Call)
    {
        $LA = [];
        list ($LA['M1'], $LA['M5'], $LA['M15']) = sys_getloadavg();

        return $LA;
    });

    setFn('Memory', function ($Call)
    {
        $Result = [];
        preg_match_all('/(\d+)/', shell_exec("free -om"), $Pockets);

        list($Result['Total'], $Result['Used'], $Result['Free']) = $Pockets[1];

        return $Result;
    });