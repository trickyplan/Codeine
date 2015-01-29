<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Console Object Support
     * @package Codeine
     * @version 8.x
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
        if (is_resource($Call['Link']))
            return fclose($Call['Link']);
        else
            return null;
    });

    setFn('Size', function ($Call)
    {
        return filesize($Call['Directory'].DS.$Call['Scope'].$Call['Log']['File']['Extension']);
    });