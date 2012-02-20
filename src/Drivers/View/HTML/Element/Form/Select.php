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

         foreach ($Call['Value'] as $Key => $Value)
             if(in_array($Key, $Call['Selected']))
                 $Options[] = '<option value="'.$Key.'" selected>'.$Value.'</option>';
             else
                $Options[] = '<option value="' . $Key . '">' . $Value . '</option>';

         $Call['Value'] = implode('', $Options);

         return F::Run ('View', 'LoadParsed', $Call,
                        array(
                             'Scope' => 'UI',
                             'ID'    => 'HTML/Form/'.(isset($Call['View'])? $Call['View'] : 'Select'),
                             'Data'  => $Call
                        ));
     });