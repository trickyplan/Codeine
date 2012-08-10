<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.6.2
     */

     self::setFn('Make', function ($Call)
     {
         if (!isset($Call['Subtype']))
             $Call['Subtype'] = 'text';

         return F::Run ('View', 'LoadParsed',
                           array(
                                'Scope' => 'Default',
                                'ID'    => 'UI/HTML/Form/Textfield',
                                'Data'  => $Call
                           ));
     });