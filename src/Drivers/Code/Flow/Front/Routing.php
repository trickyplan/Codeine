<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
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

         $Call = F::Merge($Call, $NewCall);

         $Call['Call'] = isset($Call['Call']) ? $Call['Call'] : null;

         return $Call;
     });