<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Round', function ($Call)
     {
         return ceil ($Call['Value'], $Call['Precision']);
     });