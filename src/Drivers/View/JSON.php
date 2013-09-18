<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Render', function ($Call)
    {
       $Call['Output'] = json_encode($Call['Output']['Content'],
           JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

       return $Call;
    });