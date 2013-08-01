<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Add', function ($Call)
    {
       $Call['Output']['Form'][] = $Call['Widget'];

       return $Call;
    });