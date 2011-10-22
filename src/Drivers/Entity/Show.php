<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Do', function ($Call)
     {

         $Object = F::Run(
             array(
             'Object' => array('Load', $Call['Scope']),
             'ID' => $Call['ID'])
         );

         $Call['Widgets'][] =
                      array(
                          'Place' => 'Content',
                          'Type'  => 'Object',
                          'Action' => 'Show',
                          'Template' => 'Normal',
                          'Entity' => $Call['Scope'],
                          'ID' => $Call['ID'],
                          'Value' => $Object

                  );

         return $Call;
     });