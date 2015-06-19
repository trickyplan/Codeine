<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        return strip_tags($Call['Value']); // FIXME
    });

    setFn('Read', function ($Call)
    {
        return nl2br($Call['Value']);
    });

    setFn('Where', function ($Call)
    {
        return $Call['Value'];
    });