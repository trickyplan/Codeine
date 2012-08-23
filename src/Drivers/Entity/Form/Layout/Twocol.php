<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Add', function ($Call)
    {
        $Call['Output']['Form'.($Call['IC']%2)][] = $Call['Widget'];
        return $Call;
    });