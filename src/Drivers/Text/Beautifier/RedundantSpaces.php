<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Process', function ($Call)
    {
        $Call['Value'] = preg_replace('/\s+/', ' ', $Call['Value']);

        return $Call;
     });