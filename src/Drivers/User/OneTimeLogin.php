<?php

    /* Codeine
     * @author BreathLess
     * @description Activation email 
     * @package Codeine
     * @version 7.6.2
     */

   self::setFn('Do', function ($Call)
   {
       return F::Run('Code.Run.Once', 'Prepare',
       [
            'Run' =>
                [
                    'Service' => 'Security.Auth',
                    'Method' => 'Attach',
                    'Call' =>
                        [
                            'User' => $Call['ID']
                        ]
                ]
       ]);
   });