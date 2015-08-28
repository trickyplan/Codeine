<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 8.x
     */

     setFn('Make', function ($Call)
     {
         $Call = F::Apply('View.HTML.Widget.Base', 'Make', $Call, ['Tag' => 'img']);
         return $Call['HTML'];
     });