<?php

    /* Codeine
     * @author BreathLess
     * @description: microtime() wrapper
     * @package Codeine
     * @version 7.x
     * @date 09.03.11
     * @time 16:34
     */

    setFn('Get', function ($Call)
    {
        return microtime(true);
    });
