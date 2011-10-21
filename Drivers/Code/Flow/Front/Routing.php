<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Route', function ($Call)
     {
         $NewCall = null;

         foreach ($Call['Routers'] as $Router)
             {
                 // Пробуем роутер из списка...
                 $NewCall = F::Run($Call, 
                     array(
                          '_N' => $Call['_N'].'.'.$Router,
                          '_F' => 'Route',
                          'Value' => $Call['Value']
                     )
                 );

                 // Если результат - валидный вызов, то выходим из перебора
                 if (F::isCall($NewCall))
                     break;
             }


         $Call['Value'] = $NewCall;

         return $Call;
     });