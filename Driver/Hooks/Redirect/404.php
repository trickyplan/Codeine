<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 27.03.11
     * @time 23:43
     */

    self::Fn('Redirect', function ($Call)
    {
        header('HTTP/1.1 404 Not Found');
        echo Data::Read('Layout::Error404');
        die();
    });
