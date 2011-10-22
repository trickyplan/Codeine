<?php

    /* Codeine
     * @author BreathLess
     * @description: PHP CLI Wrapper
     * @package Codeine
     * @version 6.0
     * @date 21.11.10
     * @time 2:30
     */

    self::Fn('Exec', function ($Call)
    {
        return passthru('php -r "'.$Call['Value'].'"');
    });
