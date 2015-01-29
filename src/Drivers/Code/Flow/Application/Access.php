<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.1
     */

     setFn('Check', function ($Call)
     {
         if (F::isCall($Call['Run']))
             $Call = F::Merge($Call, $Call['Run']);

         unset($Call['Decision'], $Call['Weight']);

         switch (F::Run('Security.Access', 'Check', $Call))
         {
             case true:
                 $Call = F::Hook('Access.Allowed', $Call);
             break;

             default:
                 $Call = F::Hook('Access.Denied', $Call);
             break;
         }

         return $Call;
     });