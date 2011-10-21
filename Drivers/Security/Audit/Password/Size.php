<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Check', function ($Call)
     {
         $Strength = round((strlen($Call['Value'])/12)*100);

         return $Strength>100? 100: $Strength;
     });