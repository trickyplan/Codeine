<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description:
     * @package Codeine
     * @version 8.x
     */

    setFn('Read', function ($Call)
    {
        return urldecode($Call['Value']);
    });

    setFn('Write', function ($Call)
    {
        return urlencode($Call['Value']);
    });

    setFn('Write.Call', function ($Call)
    {
        $Call['Value'] = urlencode($Call['Value']);
        return $Call;
    });
