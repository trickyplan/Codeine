<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

     self::setFn('Check', function ($Call)
     {

         $Call = F::Run('Security.Access', 'Check', F::Merge($Call, $Call['Run']));

         if ($Call['Decision'] === false)
         {
             header('HTTP/1.1 403 Forbidden');
             die();
         }


         return $Call;
     });