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
                                      'Data' => array('Load', 'Layout'),
                                      'ID' => array($Call['ID'].$Call['Context'], $Call['ID'].'.html')
                                  ));

         return $Call;
     });