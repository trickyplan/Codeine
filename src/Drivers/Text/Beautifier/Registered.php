<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Process', function ($Call)
    {
        $Call['Value'] = preg_replace('/\(r\)/', '&#174;', $Call['Value']);

        return $Call;
     });