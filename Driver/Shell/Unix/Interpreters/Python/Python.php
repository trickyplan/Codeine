<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Python Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 21.11.10
     * @time 2:27
     */

    self::Fn('Exec', function ($Call)
    {
        return passthru('python -c \''.$Call['Input'].'\'');
    });