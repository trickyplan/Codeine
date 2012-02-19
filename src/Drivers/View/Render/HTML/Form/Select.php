<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.1
     */

     self::setFn('Make', function ($Call)
     {
         $Options = array();

         $Call['Selected'] = F::Live($Call['Selected']);
         $Call['Value'] = F::Live($Call['Value']);

         foreach ($Call['Value'] as $Key => $Value)
             if(in_array($Key, $Call['Selected']))
                 $Options[] = '<option value="'.$Key.'" selected>'.$Value.'</option>';
             else
                $Options[] = '<option value="' . $Key . '">' . $Value . '</option>';

         $Call['Value'] = implode('', $Options);

         return F::Run ('Engine.Template', 'LoadParsed', $Call,
                        array(
                             'Scope' => 'UI',
                             'ID'    => 'HTML/Form/'.(isset($Call['Template'])? $Call['Template'] : 'Select'),
                             'Data'  => $Call
                        ));
     });