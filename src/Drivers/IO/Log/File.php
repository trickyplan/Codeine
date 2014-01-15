<?php

    /* Codeine
     * @author BreathLess
     * @description Console Object Support
     * @package Codeine
     * @version 7.x
     */

    setFn('Open', function ($Call)
    {
        return fopen($Call['Directory'].DS.$Call['Scope'].$Call['Log']['File']['Extension'], $Call['Log']['File']['Mode']);
    });

    setFn('Write', function ($Call)
    {
        return fwrite($Call['Link'], $Call['Data']);
    });

    setFn('Close', function ($Call)
    {
        return fclose($Call['Link']);
    });

    setFn('Size', function ($Call)
    {
        return filesize($Call['Directory'].DS.$Call['Scope'].$Call['Log']['File']['Extension']);
    });