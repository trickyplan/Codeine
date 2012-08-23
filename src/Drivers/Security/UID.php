<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Get', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Live($Call['Modes'][$Call['Mode']], $Call);
        else
            return null;
    });