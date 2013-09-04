<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Entry Point', function ($Call)
    {
        d(__FILE__, __LINE__, $Call['Request']);
        return $Call;
    });