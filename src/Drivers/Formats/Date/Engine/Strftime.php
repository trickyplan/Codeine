<?php

    /* Codeine
     * @author BreathLess
     * @description Date() engine 
     * @package Codeine
     * @version 7.x
     */

    setFn('Format', function ($Call)
    {
         return strftime($Call['Format'], $Call['Value']);
     });