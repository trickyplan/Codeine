<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */


    setFn('Get', function ($Call)
    {
        return mt_rand($Call['Min'], $Call['Max']);
    });