<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.x
     */

     setFn('Make', function ($Call)
     {
         if ($Call['Value'])
             $Call['Checked'] = 'checked';

         return F::Run ('View', 'Load', $Call,
                           array(
                                'Scope' => 'Default',
                                'ID'    => 'UI/Form/'.(isset($Call['Template'])? $Call['Template'] : 'Checkbox'),
                                'Data'  => $Call
                           ));
     });