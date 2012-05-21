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

         if (isset($Call['Options']))
            $Call['Options'] = F::Live($Call['Options']);
         else
            $Call['Options'] = array();

         $Call['Value'] = F::Live($Call['Value']);

         if (isset($Call['Multiple']))
             $Call['Name'] .= '[]';

         foreach ($Call['Options'] as $Key => $Value)
             if(in_array($Key, (array) $Call['Value']))
                 $Options[] = '<option value="'.$Key.'" selected>'.$Value.'</option>';
             else
                 $Options[] = '<option value="' . $Key . '">' . $Value . '</option>';

         $Call['Options'] = implode('', $Options);

         return F::Run ('View', 'LoadParsed',
                        array(
                             'Scope' => 'Default',
                             'ID'    => 'UI/HTML/Form/'.(isset($Call['Template'])? $Call['Template'] : 'Select'),
                             'Data'  => $Call
                        ));
     });