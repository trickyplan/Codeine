<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: PHP CLI Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 21.11.10
     * @time 2:30
     */

    self::Fn('Exec', function ($Call)
    {
        return passthru('php -r "'.$Call['Input'].'"');
    });
