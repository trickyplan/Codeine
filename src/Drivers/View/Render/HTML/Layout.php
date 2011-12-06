<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Load', function ($Call)
     {
         $Call['Layout'] = F::Run ($Call, array(
                                    '_N'      => 'Engine.View',
                                    '_F'      => 'Load'
                                  ));

         return $Call;
     });