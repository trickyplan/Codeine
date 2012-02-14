<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

    self::setFn ('Render', function ($Call)
    {
       $Call['Output'] = json_encode($Call['Output']);
// TODO Pipeline
       return $Call;
    });