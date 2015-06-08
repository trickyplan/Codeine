<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
     setFn('Send', function ($Call)
     {
         $Subscribes = F::Run('Entity', 'Read',
             [
                 'Entity' => 'Subscribe',
                 'Where'  =>
                 [
                     'Event' => $Call['Event']
                 ]
             ]);

         if (empty($Subscribes))
             ;
         else
             foreach ($Subscribes as $Subscribe)
             {
                 F::Run('IO', 'Write',
                     [
                         'Storage' => $Subscribe['Transport'],
                         'Scope'   => $Subscribe['Target'],
                         'Data'    => F::Live($Subscribe['Message'], $Call)
                     ]);
             }

         return $Call;
     });