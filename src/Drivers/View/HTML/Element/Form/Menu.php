<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.x
     */

     setFn('Make', function ($Call)
     {
         $Options = array();

         if (isset($Call['Value']))
            $Call['Value'] = F::Live($Call['Value']);
         else
            $Call['Value'] = array();

         $Call['Options'] = F::Live($Call['Options']);

         foreach ($Call['Options'] as $Option)
         {
              list ($Key, $Value) = $Option;

              if(in_array($Key, (array) $Call['Value']))
                $Options[] = '<option value="'.$Key.'" selected>'.$Value.'</option>';
              else
                $Options[] = '<option value="' . $Key . '">' . $Value . '</option>';
         }

         $Call['Options'] = implode('', $Options);

         return F::Run ('View', 'LoadParsed', $Call,
                        array(
                             'Scope' => 'Default',
                             'ID'    => 'UI/Form/'.(isset($Call['Template'])? $Call['Template'] : 'Menu'),
                             'Data'  => $Call
                        ));
     });