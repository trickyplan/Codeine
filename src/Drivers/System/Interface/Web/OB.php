<?php

    /* Codeine
     * @author BreathLess
     * @description Output Buffering
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Start', function ($Call)
    {
        ob_start();
        return $Call;
    });

    self::setFn('Finish', function ($Call)
    {
        ob_flush();
        return $Call;
    });