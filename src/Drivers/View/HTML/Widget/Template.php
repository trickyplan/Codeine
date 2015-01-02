<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

     setFn('Make', function ($Call)
     {
         $Call['Value'] = F::Run('View', 'Load', $Call);

         return $Call;
     });