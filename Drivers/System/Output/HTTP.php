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
        echo $Call['Output'];
    });

    self::Fn('Shutdown', function ($Call)
    {
        ob_flush();
    });