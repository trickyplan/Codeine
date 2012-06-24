<?php

    /* Codeine
     * @author BreathLess
     * @description Console Object Support
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Open', function ($Call)
    {
        return fopen($Call['Filename'], $Call['Mode']);
    });

    self::setFn('Write', function ($Call)
    {
        return fwrite($Call['Link'], implode("\n", (array) $Call['Data']));
    });

    self::setFn('Close', function ($Call)
    {
        return fclose($Call['Link']);
    });