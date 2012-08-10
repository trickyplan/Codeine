<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Do', function ($Call)
    {
        return explode($Call['Delimiter'], $Call['Value']);
    });