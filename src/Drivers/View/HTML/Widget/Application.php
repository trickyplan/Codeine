<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

     setFn('Make', function ($Call)
     {
         $Call['Value'] = F::Run('Code.Flow.Application', 'Run', ['Run' => $Call['Run']])['Output'];

         return $Call;
     });