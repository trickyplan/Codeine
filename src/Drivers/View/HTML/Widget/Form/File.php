<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 8.x
     */

     setFn('Make', function ($Call)
     {
         $IniVariable = ini_get('upload_max_filesize');

         if (preg_match('/^([\d]+)([KMG])/Ssu', $IniVariable, $Pockets))
         {
             $Call['Size']['Maximum'] =
                 [
                     'Value'    =>  $Pockets[1],
                     'Value10'  =>  round(
                                        F::Run('Science.Math.Conversion.Information', 'Do',
                                            [
                                                'From' => $Pockets[2].'iB',
                                                'To' => $Pockets[2].'B',
                                                'Value' => $Pockets[1]])
                                    ,F::Dot($Call, 'Size.Maximum.Precision')),
                     'Unit'     =>  $Pockets[2]
                 ];
         }

         return F::Run('View.HTML.Widget.Base', 'Make',
             $Call,
             [
                 'Tag'  => 'input',
                 'Type' => 'file'
             ]);
     });