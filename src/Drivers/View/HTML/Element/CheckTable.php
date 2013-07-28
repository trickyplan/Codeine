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

         $Cells = '';

         array_unshift($Call['Columns'], 'Corner');

         foreach ($Call['Columns'] as $Value)
             $Cells.= F::Run ('View', 'Load', $Call,
                     array(
                          'Scope' => 'Default',
                          'ID'    => $Call['Template'].'/HeadCell',
                          'Data'  => array(
                              'Value' => '<l>'.$Call['Name'].'.'.$Value.'</l>'
                          )
                     ));

          $Rows = F::Run ('View', 'Load', $Call,
                     array(
                          'Scope' => 'Default',
                          'ID'    => $Call['Template'].'/Row',
                          'Data'  => array(
                              'Value' => $Cells
                          )
                     ));

         foreach ($Call['Value'] as $RowTitle => $RowCells)
         {
             $Cells = '<td>'.$RowTitle.'</td>';

             foreach ($RowCells as $Key => $Value)
                 $Cells .=
                     F::Run ('View', 'Load', $Call,
                     array(
                          'Scope' => 'Default',
                          'ID'    => $Call['Template'].'/Cell',
                          'Data'  => array(
                              'Value' => F::Run('View.HTML.Element.Form.Checkbox', 'Make',
                                  [
                                      'Template' => 'Checkbox/Clean',
                                      'Title' => $Key,
                                      'TrueValue' => true,
                                      'Value' => $Value,
                                      'Name' => $Call['Name'].'['.$RowTitle.']['.$Key.']'])
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