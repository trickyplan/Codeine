<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Run', function ($Call)
     {
         if (isset($Call['Input']['Required']) && $Call['Input']['Required'])
         {

             if (!isset($Call['Value'][$Call['Name']]))
             {
                 if (isset($Call['Input']['Default']) && $Call['Input']['AutoCorrect']['Required'])
                     $Call['Value'][$Call['Name']] = $Call['Input']['Default'];
                 else
                     echo 'Argument not exist!';
             }
         }

         return $Call['Value'];
     });