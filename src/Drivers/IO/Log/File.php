<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Console Object Support
     * @package Codeine
     * @version 8.x
     */

    setFn('Open', function ($Call)
    {
        if (file_exists($Call['Directory'].DS.$Call['Scope'].$Call['Log']['File']['Extension']))
            return fopen($Call['Directory'].DS.$Call['Scope'].$Call['Log']['File']['Extension'], $Call['Log']['File']['Mode']);
        else
            return null;
    });

    setFn('Write', function ($Call)
    {
        if (is_resource($Call['Link']))
            return fwrite($Call['Link'], $Call['Data']);
        else
            return null;
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