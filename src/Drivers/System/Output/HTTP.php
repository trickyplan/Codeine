<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn('Initialize', function ($Call)
    {
        ob_start();
    });

    self::setFn('Do', function ($Call)
    {
        if (isset($Call['Headers']))
        foreach ($Call['Headers'] as $Key => $Value)
            header($Key.': '.$Value);

        echo $Call['Output'];
    });

    self::setFn('Shutdown', function ($Call)
    {
        ob_flush();
    });