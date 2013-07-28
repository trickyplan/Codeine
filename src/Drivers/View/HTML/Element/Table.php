<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

     setFn('Make', function ($Call)
     {
         // TODO Header Support

         $Rows = '';
         // $Columns = array_keys(current($Call['Value']));

         // $Cells = '';

         /*foreach ($Columns as $Value)
             $Cells.= F::Run ('View', 'Load', $Call,
                     array(
                          'Scope' => 'Default',
                          'ID'    => 'UI/Table/Cell',
                          'Data'  => array(
                              'Value' => $Value
                          )
                     ));

          $Rows = F::Run ('View', 'Load', $Call,
                     array(
                          'Scope' => 'Default',
                          'ID'    => 'UI/Table/Row',
                          'Data'  => array(
                              'Value' => $Cells
                          )
                     ));*/

         foreach ($Call['Value'] as $Title => $Row)
         {
             $Cells = '';

             foreach ($Row as $Key => $Value)
                 $Cells .= F::Run ('View', 'Load', $Call,
                     array(
                          'Scope' => 'Default',
                          'ID'    => $Call['Template'].'/Cell',
                          'Data'  => array(
                              'Key' => $Key,
                              'Value' => $Value
                          )
                     ));

             $Rows.= F::Run ('View', 'Load', $Call,
                     array(
                          'Scope' => 'Default',
                          'ID'    => $Call['Template'].'/Row',
                          'Data'  => array(
                              'Value' => $Cells
                          )
                     ));
         }

         if(!isset($Call['Headless']))
            return F::Run ('View', 'Load', $Call,
                           array(
                                'Scope' => 'Default',
                                'ID'    => $Call['Template'].'/Body',
                                'Data'  => array(
                                    'Value' => $Rows
                                )
                           ));
         else
            return $Rows;
     });