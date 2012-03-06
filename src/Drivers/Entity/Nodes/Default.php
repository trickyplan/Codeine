<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Process', function ($Call)
    {
        if (!isset($Call['Data'][$Call['Name']]))
        {
            if (isset($Call['Node']['Default']))
                return F::Live($Call['Node']['Default']);
            else
                return null;
        }
        else
            return $Call['Data'][$Call['Name']];
    });