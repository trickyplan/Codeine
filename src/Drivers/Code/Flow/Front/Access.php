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

         $Call = F::Apply('Security.Access', 'Check', $Call);

         if ($Call['Decision'] == false)
         {
             $Call = F::Hook('Access.Denied', $Call);
             $Call['Failure'] = true;
         }

         return $Call;
     });