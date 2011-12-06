<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Get', function ($Call)
     {
         $Data = array();
         
         foreach ($_POST as $Key => $Value)
             $Data[strtr($Key,'_','.')] = $Value;

         return $Data;
     });