<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn('beforeTrace', function ($Call)
    {
        echo '<pre>';
    });

    self::setFn('Run', function ($Call)
    {
        echo str_pad('',($Call['Stack']->count()-2), "\t").F::hashCall($Call['Value'])."\n";
    });

    self::setFn('afterTrace', function ($Call)
    {
        echo '</pre>';
    });