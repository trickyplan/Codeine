<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

     setFn('Check', function ($Call)
     {
         if (F::isCall($Call['Run']))
             $Call = F::Merge($Call, $Call['Run']);

         unset($Call['Decision'], $Call['Weight']);

         if (F::Run('Security.Access', 'Check', $Call))
             $Call = F::Hook('Access.Allowed', $Call);
         else
             $Call = F::Hook('Access.Denied', $Call);

         return $Call;
     });