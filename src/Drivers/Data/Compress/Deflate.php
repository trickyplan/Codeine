<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 2019.x
     */

    setFn('Read', function ($Call)
    {
        return gzinflate($Call['Data']);
    });

    setFn('Write', function ($Call)
    {
        return gzdeflate($Call['Data'], $Call['Compress']['Deflate']['Level']);
    });