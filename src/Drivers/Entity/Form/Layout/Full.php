<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Add', function ($Call)
    {
        $Call['Output']['Form'][] = $Call['Widget'];
        return $Call;
    });