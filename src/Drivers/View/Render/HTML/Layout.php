<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Load', function ($Call)
     {
         $Call['Layout'] = F::Run(array(
                                      '_N' => 'Engine.Data',
                                      '_F' => 'Load',
                                      'Scope' => 'Layout',
                                      'ID' => array($Call['ID'].$Call['Context'], $Call['ID'].'.html')
                                  ));

         return $Call;
     });