<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

     self::setFn('Catch', function ($Call)
     {
         $Result = null;
         if (isset($Call['Hooks'][$Call['Message']]))
         {
             foreach($Call['Hooks'][$Call['Message']] as $Hook)
                 $Result = F::Run($Call, $Hook);
         }
         return $Result;
     });