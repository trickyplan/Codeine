<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

     self::setFn('Check', function ($Call)
     {
         if (F::isCall($Call['Run']))
             $Call = F::Merge($Call, $Call['Run']);

         unset($Call['Decision'], $Call['Weight']);

         $Call = F::Run('Security.Access', 'Check', $Call);

         if ($Call['Decision'] == false)
             $Call = F::Hook('Access.Denied', $Call);

         return $Call;
     });