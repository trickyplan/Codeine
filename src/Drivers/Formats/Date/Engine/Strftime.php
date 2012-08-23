<?php

    /* Codeine
     * @author BreathLess
     * @description Date() engine 
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Format', function ($Call)
    {
         return strftime($Call['Format'], $Call['Value']);
     });