<?php

    /* Codeine
     * @author BreathLess
     * @description Console Object Support
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Open', function ($Call)
    {
        return array ();
    });

    self::setFn('Write', function ($Call)
    {
        PhpConsole::debug($Call['Data']);

        return $Call;
    });

    self::setFn('Close', function ($Call)
    {


        return $Call;
    });