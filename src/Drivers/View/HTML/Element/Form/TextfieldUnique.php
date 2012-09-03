<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.x
     */

     self::setFn('Make', function ($Call)
     {
         $Call['HTML'] = F::Run('View.HTML.Element.Base', 'Make', $Call,
             [
                 'Tag' => 'input',
                 'data-value' => $Call['Value'],
                 'data-unique' => $Call['Name']
             ]);

         return F::Run ('View', 'LoadParsed',
                           array(
                                'Scope' => 'Default',
                                'ID'    => 'UI/Form/TextfieldUnique',
                                'Data'  => $Call
                           ));
     });