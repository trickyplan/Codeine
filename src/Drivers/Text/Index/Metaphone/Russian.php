<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Get', function ($Call)
    {
        $Call['Value'] = strtr(mb_strtolower(preg_replace('/(.)\\1+/','', $Call['Value'])), $Call['Replace']);
        return preg_replace('/(.)\\1+/SsUu', '\1', $Call['Value']);
    });