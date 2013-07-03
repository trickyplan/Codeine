<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.x
     */

     setFn('Make', function ($Call)
     {
         if (is_array($Call['Value']))
             $Call['Value'] = implode(',',$Call['Value']);

         $Call['HTML'] = F::Run('View.HTML.Element.Base', 'Make', $Call, ['Tag' => 'input', 'Type' => (isset($Call['Subtype'])? $Call['Subtype']: 'text')]);

         return F::Run ('View', 'LoadParsed',
                           [
                                'Scope' => 'Default',
                                'ID'    => 'UI/Form/Textfield',
                                'Data'  => $Call
                           ]);
     });