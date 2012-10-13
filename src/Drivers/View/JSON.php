<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn ('Render', function ($Call)
    {
       $Call['Headers']['Content-type:'] = 'text/json';
       $Call['Output'] = json_encode($Call['Output']['Content']);

       return $Call;
    });