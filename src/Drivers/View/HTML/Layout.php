<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

     self::setFn('Load', function ($Call)
     {
         $Call['Layout'] = F::Run ('View', 'Load', array('Scope' => 'Default'), $Call);

         return $Call;
     });