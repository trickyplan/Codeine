<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Validate', function ($Call)
     {
         return in_array($Call['Value'], $Call['Argument']['Values']);
     });