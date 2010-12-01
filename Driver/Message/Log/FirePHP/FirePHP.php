<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: FirePHP Logger
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 18:37
     */

    self::Fn('Connect', function ($Call)
    {
        include Data::Locate('Classes','FirePHP/FirePHP.php');
        return FirePHP::getInstance(true);
    });

    self::Fn('Send', function ($Call)
    {

    });

    self::Fn('Disconnect', function ($Call)
    {
        
    });