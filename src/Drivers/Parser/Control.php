<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {

        return $Call;
    });

    setFn('URL', function ($Call)
    {
       return F::Run('Parser.Control.URL', 'Do', $Call);
    });