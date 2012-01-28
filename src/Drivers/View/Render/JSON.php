<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Process', function ($Call)
    {
       $Call['Output'] = json_encode($Call['JSON']);

       return $Call;
    });