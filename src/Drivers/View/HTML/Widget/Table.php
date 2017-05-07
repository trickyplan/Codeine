<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

     setFn('Make', function ($Call)
     {
         // TODO Header Support

         $Call['Rows'] = '';

         foreach ($Call['Value'] as $Row)
         {
             $Cells = '';

             if (isset($Row['_Class']))
             {
                 $RowClass = $Row['_Class'];
                 unset($Row['_Class']);
             }
             else
                 $RowClass = '';

             foreach ($Row as $Key => $Value)
                 $Cells .= F::Run ('View', 'Load', $Call,
                     [
                          'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                          'ID'    => 'Table/Cell',
                          'Data'  =>
                          [
                              'Key' => $Key,
                              'Value' => $Value
                          ]
                     ]);

             $Call['Rows'].= F::Run ('View', 'Load', $Call,
                     [
                          'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                          'ID'    => 'Table/Row',
                          'Data'  =>
                          [
                              'Value' => $Cells,
                              'Class' => $RowClass
                          ]
                     ]);
         }

         return $Call;
     });