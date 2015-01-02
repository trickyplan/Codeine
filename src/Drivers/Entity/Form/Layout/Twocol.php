<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Add', function ($Call)
    {
        $Call['Output']['Form'.($Call['IC']%2)][] = $Call['Widget'];
        return $Call;
    });