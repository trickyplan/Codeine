<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Add', function ($Call)
    {
        $Call['Output']['Form'][] = $Call['Widget'];
        return $Call;
    });