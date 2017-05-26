<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.1
     */

     setFn('beforeAPIRun', function ($Call)
     {
         $Call['API']['Response']['Access'] = 'XXX';

         $Call['API']['Response']['Access'] = F::Run('Security.Access', 'Check', $Call['API']['Request']);

         if ($Call['API']['Response']['Access'] === 401)
         {
             $Call['Run'] =
                [
                    'Service' => 'User.Login',
                    'Method'  => 'Do',
                    'Zone' => 'Default'
                ];
         }

         if ($Call['API']['Response']['Access'])
             $Call = F::Hook('onAPIAccessAllowed', $Call);
         else
             $Call = F::Hook('onAPIAccessDenied', $Call);

         return $Call;
     });