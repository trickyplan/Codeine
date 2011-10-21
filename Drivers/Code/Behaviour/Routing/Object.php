<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Route', function ($Call)
     {
         if (is_array($Call['Value']) && isset($Call['Value']['Object']))
         {
             $Call = F::Merge($Call['Value'], array(
                                                  '_N' => 'Engine.Object',
                                                  '_F' => $Call['Value']['Object'][0],
                                                  'Scope' => $Call['Value']['Object'][1]
                                             ));
             unset($Call['Object']);

             return $Call;
         }
         else
            return null;
     });