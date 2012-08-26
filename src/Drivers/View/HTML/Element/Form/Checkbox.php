<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.x
     */

     self::setFn('Make', function ($Call)
     {
         if ($Call['Value'])
             $Call['Checked'] = 'checked';

         return F::Run ('View', 'LoadParsed', $Call,
                           array(
                                'Scope' => 'Default',
                                'ID'    => 'UI/HTML/Form/'.(isset($Call['Template'])? $Call['Template'] : 'Checkbox'),
                                'Data'  => $Call
                           ));
     });