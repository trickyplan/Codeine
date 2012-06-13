<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Write', function ($Call)
    {
        $Date = strptime($Call['Value'],'%d.%m.%Y');
        return mktime(0,0,0, 1+$Date['tm_mon'], $Date['tm_mday'], 1900+$Date['tm_year']);
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });