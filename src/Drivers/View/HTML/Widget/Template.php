<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

     setFn('Make', function ($Call)
     {
         $Call['Value'] = F::Run('View', 'Load', $Call, ['RTTL' => 3600]);

         return $Call;
     });