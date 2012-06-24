<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Get', function ($Call)
    {
         return F::Live($Call['ID']). $Call['Map'] [$Call['Value']['type']];
    });