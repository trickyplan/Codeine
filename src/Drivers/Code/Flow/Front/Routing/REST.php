<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Route', function ($Call)
     {
         $Entities = array('User');
         $NewCall = null;

         if (is_string($Call['Value']) && mb_strpos($Call['Value'], '/') !== false)
         {
             if (count($Slices = explode('/', substr($Call['Value'], 1))) == 2)
             {
                 $NewCall = array('_N' => 'Entity', '_F' => 'Do');
                 list($NewCall['Scope'], $NewCall['ID']) = $Slices;

                 if (in_array($NewCall['Scope'], $Entities))
                 {
                     if (isset($Call['Map'][$_SERVER['REQUEST_METHOD']]))
                         $NewCall['_N'].= '.'.$Call['Map'][$_SERVER['REQUEST_METHOD']];
                     else
                     {
                         F::Run(array(
                                    'Send'  => 'Event',
                                    'Message' => $Call['_N'].'.Failed',
                                    'Call' => F::hashCall($Call)
                               ));
                         return null;
                     }
                 }
             }

         }

         return $NewCall;
     });