<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

     self::setFn('Make', function ($Call)
     {
         return F::Run ('Engine.Template', 'Load', array('Scope' => 'Static', 'ID' => $Call['Value']));
     });