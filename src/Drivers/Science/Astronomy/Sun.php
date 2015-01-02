<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (!isset($Call['Time']))
            $Call['Time'] = F::Run('System.Time', 'Get');

        $Output = date_sun_info($Call['Time'], $Call['Lattitude'], $Call['Longitude']);

        $Output['daylight'] = $Output ['sunset'] - $Output ['sunrise'];
        if (isset($Call['Sun Key']))
            $Output = $Output[$Call['Sun Key']];

        return $Output;
    });