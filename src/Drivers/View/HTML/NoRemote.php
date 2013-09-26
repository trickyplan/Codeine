<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        $Call['Output'] = preg_replace('@a href="http://(.*)"@', 'a href="/go/$1"', $Call['Output']);
        return $Call;
    });