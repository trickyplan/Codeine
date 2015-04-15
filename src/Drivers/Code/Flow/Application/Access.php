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
                 F::Log('Access to Application '.self::hashCall($Call['Run']).' allowed', LOG_NOTICE, 'Security');
                 $Call = F::Hook('Access.Allowed', $Call);
             break;

             default:
                 F::Log('Access to Application '.self::hashCall($Call['Run']).' denied', LOG_NOTICE, 'Security');
                 $Call = F::Hook('Access.Denied', $Call);
             break;
         }

         return $Call;
     });