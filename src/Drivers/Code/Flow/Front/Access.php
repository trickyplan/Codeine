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

         $Call = F::Run('Security.Access', 'Check', $Call);

         if ($Call['Decision'] === false)
         {
             header('HTTP/1.1 403 Forbidden');
         }

         return $Call;
     });