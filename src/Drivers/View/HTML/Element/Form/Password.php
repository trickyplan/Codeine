<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.x
     */

     setFn('Make', function ($Call)
     {
         $Call['HTML'] = F::Run('View.HTML.Element.Base', 'Make', $Call, ['Tag' => 'input', 'Type' => 'password']);

         return F::Run ('View', 'LoadParsed', $Call,
                           array(
                                'Scope' => 'Default',
                                'ID'    => 'UI/Form/Password/'.$Call['Mode'],
                                'Data'  => $Call
                           ));
     });