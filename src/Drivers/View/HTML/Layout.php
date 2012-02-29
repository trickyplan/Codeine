<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Load', function ($Call)
     {
         $Call['Layout'] = F::Run ('View', 'Load', array('Scope' => 'Default'), $Call);
         return $Call;
     });