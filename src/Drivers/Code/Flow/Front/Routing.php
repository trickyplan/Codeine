<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Route', function ($Call)
     {
         $NewCall = null;

         foreach ($Call['Routers'] as $Router)
         {
             // Пробуем роутер из списка...
             $NewCall = F::Run($Router['Service'], $Router['Method'], $Call);

             // Если результат - валидный вызов, то выходим из перебора
             if (F::isCall($NewCall))
                 break;
         }

         $Call['Value'] = $NewCall;

         return $Call;
     });