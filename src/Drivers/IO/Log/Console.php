<?php

    /* Codeine
     * @author BreathLess
     * @description Console Object Support
     * @package Codeine
     * @version 7.x
     */

    setFn('Open', function ($Call)
    {
        return array ();
    });

    setFn('Write', function ($Call)
    {
        PhpConsole::debug($Call['Data']);

        return $Call;
    });

    setFn('Close', function ($Call)
    {


        return $Call;
    });