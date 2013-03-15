<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Get', function ($Call)
    {
        if (isset($Call['System']['Time']['Modes'][$Call['System']['Time']['Mode']]))
        {
            return F::Live($Call['System']['Time']['Modes'][$Call['System']['Time']['Mode']], $Call)+$Call['System']['Time']['Increment'];
        }
        else
            return null;
    });