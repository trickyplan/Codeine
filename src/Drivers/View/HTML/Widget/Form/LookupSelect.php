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

         if (isset($Call['Selected']))
            $Call['Selected'] = F::Live($Call['Selected']);
         else
            $Call['Selected'] = array();

         $Call['Value'] = F::Live($Call['Value']);


         foreach ($Call['Value'] as $Key => $Value)
             if(in_array($Key, (array)$Call['Selected']))
                 $Options[] = '<option value="'.$Key.'" selected>'.$Value.'</option>';
             else
                 $Options[] = '<option value="' . $Key . '">' . $Value . '</option>';

         $Call['Value'] = implode('', $Options);

         return $Call;
     });