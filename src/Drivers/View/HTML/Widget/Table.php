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

         $Call['Rows'] = '';

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

          $Call['Rows'] = F::Run ('View', 'Load', $Call,
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
                     [
                          'Scope' => $Call['Widget Set'].'/Widgets',
                          'ID'    => 'Table/Cell',
                          'Data'  =>
                          [
                              'Key' => $Key,
                              'Value' => $Value
                          ]
                     ]);

             $Call['Rows'].= F::Run ('View', 'Load', $Call,
                     [
                         'Scope' => $Call['Widget Set'].'/Widgets',
                          'ID'    => 'Table/Row',
                          'Data'  =>
                          [
                              'Value' => $Cells
                          ]
                     ]);
         }

         return $Call;
     });