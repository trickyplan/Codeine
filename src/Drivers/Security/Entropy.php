<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    self::setFn('Do', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Live($Call['Modes'][$Call['Mode']], $Call);
        else
            return null;
    });