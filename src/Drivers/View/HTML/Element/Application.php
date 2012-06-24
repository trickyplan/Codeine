<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

     self::setFn('Make', function ($Call)
     {
         $Result = F::Run('Code.Flow.Application', 'Run', array('Context' => 'app', 'Run' => $Call['Run']));
         return $Result['Output'];
     });