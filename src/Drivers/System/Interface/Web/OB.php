<?php

    /* Codeine
     * @author BreathLess
     * @description Output Buffering
     * @package Codeine
     * @version 7.4.5
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