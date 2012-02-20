<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Render', function ($Call)
    {
       $Call['Headers']['Content-type:'] = 'text/json';
       $Call['Output'] = json_encode($Call['Output']);

       return $Call;
    });