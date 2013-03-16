<?php

    /* Codeine
     * @author BreathLess
     * @description Activation email 
     * @package Codeine
     * @version 7.x
     */

   setFn('Do', function ($Call)
   {
       return F::Run('Code.Run.Once', 'Prepare',
       [
            'Run' =>
                [
                    'Service' => 'Session',
                    'Method' => 'Write',
                    'Call' =>
                        [
                            'Data' => ['User' => $Call['ID']]
                        ]
                ]
       ]);
   });