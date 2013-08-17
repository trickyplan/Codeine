<?php

    /* Codeine
     * @author BreathLess
     * @description Minifier 
     * @package Codeine
     * @version 7.x
     */

    setFn ('Process', function ($Call)
    {
        $Call['Output'] = preg_replace ('/^\\s{2,}|\\s{2,}$/m', '', $Call['Output']);

        return $Call;
    });