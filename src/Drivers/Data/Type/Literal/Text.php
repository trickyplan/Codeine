<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Write', function ($Call)
    {
        return strip_tags($Call['Value'], '<b><i><u>'); // FIXME
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });