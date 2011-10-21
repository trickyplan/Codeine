<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Route', function ($Call)
     {
         if (is_array($Call['Value']))
         {
             if (isset($Call['Value']['Send']))
                    return F::Merge($Call['Value'], array(
                              '_N' => 'Engine.Message',
                              '_F' => 'Send',
                              'To' => $Call['Value']['Send']
                                     ));
             if (isset($Call['Value']['Receive']))
             {
                    return F::Merge($Call['Value'], array(
                               '_N' => 'Engine.Message',
                               '_F' => 'Receive',
                               'To' => $Call['Value']['Receive']
                                      ));
             }
         }
         else
            return null;
     });