<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Initialize', function ($Call)
    {
        ob_start();
    });

    self::Fn('Do', function ($Call)
    {
        if (isset($Call['Headers']))
        foreach ($Call['Headers'] as $Key => $Value)
            header($Key.': '.$Value);

        echo $Call['Output'];
    });

    self::Fn('Shutdown', function ($Call)
    {
        ob_flush();
    });