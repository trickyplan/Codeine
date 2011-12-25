<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

     self::setFn('Load', function ($Call)
     {
         $Call['Layout'] = F::Run ('Engine.Template', 'Load', $Call, array('Scope' => 'Layout'));

         return $Call;
     });