<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Route', function ($Call)
     {
         if (is_array($Call['Value']) && isset($Call['Value']['Data']))
         {

             $Call = F::Merge($Call['Value'], array(
                                                  '_N' => 'Engine.Data',
                                                  '_F' => $Call['Value']['Data'][0],
                                                  'Storage' => $Call['Value']['Data'][1]
                                             ));
             unset($Call['Data']);

             return $Call;
         }
         else
            return null;
     });