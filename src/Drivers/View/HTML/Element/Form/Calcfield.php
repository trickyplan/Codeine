<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.x
     */

     self::setFn('Make', function ($Call)
     {
         return F::Run ('View', 'LoadParsed',
                           array(
                                'Scope' => 'Default',
                                'ID'    => 'UI/HTML/Form/Calcfield',
                                'Data'  => $Call
                           ));
     });