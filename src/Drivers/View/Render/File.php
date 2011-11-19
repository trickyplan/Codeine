<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('Process', function ($Call)
    {
       $Call['Headers']['Content-type'] = 'text/plain';
       $Call['Output'] = $Call['Value'];

       return $Call;
    });