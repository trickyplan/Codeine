<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Do', function ($Call)
     {
         $Call['Value'] *= $Call['Units'][$Call['Type']][$Call['From']];
         $Call['Value'] /= $Call['Units'][$Call['Type']][$Call['To']];

         return $Call['Value'];
     });