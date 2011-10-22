<?php

    /* Codeine
     * @author BreathLess
     * @description Deferred Run 
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Do', function ($Call)
     {
         $CallID = F::hashCall($Call['Value']);

         return F::Run(
             array(
                 'Send' => 'Deferred',
                 'Scope' => 'Deferred',
                 'Message' =>
                     array(
                         'ID' => $CallID,
                         'Time' => microtime(true),
                         'Call' => $Call['Value']
                     )
             )
         );

     });