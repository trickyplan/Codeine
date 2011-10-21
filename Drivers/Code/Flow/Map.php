<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Array', function ($Call)
     {
         $Results = array();
         
         foreach ($Call['Array'] as $Index => $Item)
             $Results[$Index] = F::Run(
                 F::Merge(
                     $Call['Fn'],
                     array($Call['Key'] => $Item)
                 )
             );

         return $Results;
     });