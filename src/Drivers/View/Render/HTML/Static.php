<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Make', function ($Call)
     {
         return F::Run ('Engine.Template', 'Load', array('Scope' => 'Static', 'ID' => $Call['Value']));
     });