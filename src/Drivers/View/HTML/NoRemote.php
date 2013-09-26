<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        $Call['Output'] = preg_replace('@href="http://(.*)"@', 'href=/go/$1', $Call['Output']);
        return $Call;
    });