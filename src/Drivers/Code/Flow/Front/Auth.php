<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

     self::setFn('Audit', function ($Call)
     {
         if (isset($_COOKIE['SID']))
         {
             $Session = F::Run('Engine.Entity', 'Read',
             array(
                 'Entity' => 'Session',
                 'Where' =>
                     array(
                         'SID' => $_COOKIE['SID']
                     )
             ));

             if (!empty($Session))
                $Call['User'] = $Session[0]['User'];
         }

         return $Call;
     });