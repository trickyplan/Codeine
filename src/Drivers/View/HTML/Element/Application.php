<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

     self::setFn('Make', function ($Call)
     {
         return F::Run('Code.Flow.Application', 'Run', array('Run' => $Call['Run']))['Output'];
     });