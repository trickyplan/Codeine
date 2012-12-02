<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        foreach ($Call['Sensors'] as $Sensor)
            $Sensors[$Sensor] = F::Run(null, $Sensor, $Call);

        return $Sensors;
    });

    setFn('Version', function ($Call)
    {
        return F::loadOptions('Version')['Codeine'];
    });