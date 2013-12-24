<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.x
     */

     setFn('Make', function ($Call)
     {
         $Call['Value'] = '';
         $Call['HTML'] = F::Run('View.HTML.Widget.Base', 'Make', $Call, ['Tag' => 'input', 'Type' => 'password']);

         return $Call;
     });