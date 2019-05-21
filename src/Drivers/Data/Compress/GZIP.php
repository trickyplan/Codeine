<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 2019.x
     */

    setFn('Read', function ($Call)
    {
        return gzdecode($Call['Data']);
    });

    setFn('Write', function ($Call)
    {
        return gzencode($Call['Data'], $Call['Compress']['GZIP']['Level']);
    });