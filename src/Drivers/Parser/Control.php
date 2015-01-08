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
       return F::Run('Parser.URL', 'Do', $Call);
    });

    setFn('Numbered', function ($Call)
    {
       return F::Run('Parser.Numbered', 'Do', $Call);
    });

    setFn('Spider', function ($Call)
    {
       return F::Run('Parser.Spider', 'Do', $Call);
    });