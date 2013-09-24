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

         $Call = F::Apply('View.HTML.Widget.Base', 'Make', $Call, ['Tag' => 'textarea']);

         return $Call;
     });