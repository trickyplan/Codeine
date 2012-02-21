<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Make', function ($Call)
     {
         return F::Run ('IO', 'Read', array('Storage' => 'Static', 'Where' => $Call['Value']), $Call);
     });