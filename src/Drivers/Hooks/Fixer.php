<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Catch', function ($Call)
     {
         if (isset($Call['Hooks'][$Call['Message']]))
         {
             foreach($Call['Hooks'][$Call['Message']] as $Hook)
                 $Result = F::Run($Call, $Hook);
         }
         return $Result;
     });