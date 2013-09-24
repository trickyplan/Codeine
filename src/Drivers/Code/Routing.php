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
             $NewCall = F::Run('Code.Routing.'.$Router, null, $Call);

             // Если результат - валидный вызов, то выходим из перебора
             if (F::isCall($NewCall['Run']))
                 break;
         }

         if (isset($NewCall['Run']))
            $Call = $NewCall;

         return $Call;
     });

    setFn('Reverse', function ($Call)
    {
        return $Call; // Disabled
//        foreach ($Call['Routers'] as $Router)
        $Router = 'Regex';
        {
             // Пробуем роутер из списка...
             $Call = F::Apply('Code.Routing.'.$Router, null, $Call);

             // Если результат - валидный вызов, то выходим из перебора
        /*     if (isset($Call['Link']))
                 break;*/
         }

        return $Call['Link'];
    });