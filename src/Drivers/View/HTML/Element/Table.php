<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

     self::setFn('Make', function ($Call)
     {
         // TODO Header Support
         $Rows = '';

         foreach ($Call['Value'] as $Title => $Row)
         {
             $Cells = '';

             foreach ($Row as $Key => $Value)
                 $Cells .= F::Run ('View', 'LoadParsed', $Call,
                     array(
                          'Scope' => 'Default',
                          'ID'    => 'UI/HTML/Table/Cell',
                          'Data'  => array(
                              'Key' => $Key,
                              'Value' => $Value
                          )
                     ));

             $Rows.= F::Run ('View', 'LoadParsed', $Call,
                     array(
                          'Scope' => 'Default',
                          'ID'    => 'UI/HTML/Table/Row',
                          'Data'  => array(
                              'Value' => $Cells
                          )
                     ));
         }

         if(!isset($Call['Headless']))
            return F::Run ('View', 'LoadParsed', $Call,
                           array(
                                'Scope' => 'Default',
                                'ID'    => 'UI/HTML/Table/Body',
                                'Data'  => array(
                                    'Value' => $Rows
                                )
                           ));
         else
            return $Rows;
     });