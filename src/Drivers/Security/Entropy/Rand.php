<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Get', function ($Call)
    {
        return rand($Call['Min'], $Call['Max']);
    });