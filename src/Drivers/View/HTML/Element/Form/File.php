<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.x
     */

     setFn('Make', function ($Call)
     {
         return F::Run ('View', 'Load', $Call,
                           array(
                                'Scope' => 'Default',
                                'ID'    => 'UI/Form/File',
                                'Data'  => $Call
                           ));
     });