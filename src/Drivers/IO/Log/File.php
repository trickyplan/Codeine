<?php

    /* Codeine
     * @author BreathLess
     * @description Console Object Support
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Open', function ($Call)
    {
        return fopen($Call['Filename'], 'a+');
    });

    self::setFn('Write', function ($Call)
    {
        return fwrite($Call['Link'], $Call['Data']."\n");
    });

    self::setFn('Close', function ($Call)
    {
        return fclose($Call['Link']);
    });