<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Filter', function ($Call)
    {
        $Call['Request'] = filter_var_array($Call['Request'], FILTER_SANITIZE_SPECIAL_CHARS);
        return $Call;
    });