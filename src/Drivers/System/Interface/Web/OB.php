<?php

    /* Codeine
     * @author BreathLess
     * @description Output Buffering
     * @package Codeine
     * @version 7.x
     */

    setFn('Start', function ($Call)
    {
        ob_start();
        return $Call;
    });

    setFn('Finish', function ($Call)
    {
        ob_flush();
        return $Call;
    });