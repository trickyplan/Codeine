<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

     setFn('Route', function ($Call)
     {
         foreach ($Call['Routers'] as $Router)
         {
             // Пробуем роутер из списка...
             $NewCall = F::Run($Router['Service'], $Router['Method'], $Call);

             // Если результат - валидный вызов, то выходим из перебора
             if (F::isCall($NewCall['Run']))
                 break;
         }

         if (isset($NewCall['Run']))
            $Call = $NewCall;

         return $Call;
     });