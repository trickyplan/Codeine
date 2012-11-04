<?php

    /* Codeine
     * @author BreathLess
     * @description Console Object Support
     * @package Codeine
     * @version 7.x
     */

    setFn('Open', function ($Call)
    {
        return fopen($Call['Filename'], $Call['Mode']);
    });

    setFn('Write', function ($Call)
    {
        return fwrite($Call['Link'], implode("\n", (array) $Call['Data']));
    });

    setFn('Close', function ($Call)
    {
        return fclose($Call['Link']);
    });