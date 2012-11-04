<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Get', function ($Call)
    {
        return $Call['Min']+lcg_value()*$Call['Max']-$Call['Min'];
    });