<?php

    /* Codeine
     * @author BreathLess
     * @description: microtime() wrapper
     * @package Codeine
     * @version 7.0
     * @date 09.03.11
     * @time 16:34
     */

    self::setFn('Get', function ($Call)
    {
        return microtime(true);
    });
