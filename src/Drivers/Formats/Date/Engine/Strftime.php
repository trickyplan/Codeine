<?php

    /* Codeine
     * @author BreathLess
     * @description Date() engine 
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Format', function ($Call)
    {
         return strftime($Call['Format'], $Call['Value']);
     });