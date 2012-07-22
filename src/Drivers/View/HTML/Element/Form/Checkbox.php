<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.4.5
     */

     self::setFn('Make', function ($Call)
     {
         if ($Call['Value'])
             $Call['Checked'] = 'checked';

         return F::Run ('View', 'LoadParsed', $Call,
                           array(
                                'Scope' => 'Default',
                                'ID'    => 'UI/HTML/Form/Checkbox',
                                'Data'  => $Call
                           ));
     });