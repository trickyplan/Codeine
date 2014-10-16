<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Get', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            ;
        else
            $Call['Mode'] = 'Normal';

        return F::Live($Call['Modes'][$Call['Mode']], $Call)+$Call['Increment'];
    });