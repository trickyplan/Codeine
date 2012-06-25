<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Write', function ($Call)
    {
        return nl2br(strip_tags($Call['Value'], '<b><i><u>')); // FIXME
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });