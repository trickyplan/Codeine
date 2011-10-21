<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn('beforeTrace', function ($Call)
    {
        echo '<pre>';
    });

    self::Fn('Run', function ($Call)
    {
        echo str_pad('',($Call['Stack']->count()-2), "\t").F::hashCall($Call['Value'])."\n";
    });

    self::Fn('afterTrace', function ($Call)
    {
        echo '</pre>';
    });