<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Process', function ($Call)
    {
       $Call['Output'] = json_encode($Call['JSON']);

       return $Call;
    });