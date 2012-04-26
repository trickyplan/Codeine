<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Make', function ($Call)
     {
         $Options = array();

         if (isset($Call['Selected']))
            $Call['Selected'] = F::Live($Call['Selected']);
         else
            $Call['Selected'] = array();

         $Call['Value'] = F::Live($Call['Value']);

         if (isset($Call['Multiple']))
             $Call['Name'] .= '[]';

         foreach ($Call['Value'] as $Key => $Value)
             if(in_array($Key, (array)$Call['Selected']))
                 $Options[] = '<option value="'.$Key.'" selected>'.$Value.'</option>';
             else
                 $Options[] = '<option value="' . $Key . '">' . $Value . '</option>';

         $Call['Value'] = implode('', $Options);

         return F::Run ('View', 'LoadParsed', $Call,
                        array(
                             'Scope' => 'Default',
                             'ID'    => 'UI/HTML/Form/'.(isset($Call['Template'])? $Call['Template'] : 'Select'),
                             'Data'  => $Call
                        ));
     });