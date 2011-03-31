<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 25.02.11
     * @time 16:29
     */
    include (Engine . 'Library/PhpConsole.php');
    PhpConsole::start(true, true, dirname(__FILE__));

    self::Fn('Catch', function ($Call)
    {
        return debug(json_encode($Call['Data']));
    });
