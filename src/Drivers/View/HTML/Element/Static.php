<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Make', function ($Call)
     {
         return F::Run ('View', 'Load', array('Scope' => 'Static', 'ID' => $Call['Value']));
     });