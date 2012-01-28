<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

     self::setFn('Run', function ($Call)
     {
         if (isset($Call['Hooks'][$Call['On']]))
             foreach ($Call['Hooks'][$Call['On']] as $Name => $Hook)
                 $Call = F::Run($Hook['Service'], $Hook['Method'], $Call, isset($Hook['Call'])? $Hook['Call']: array());

         return $Call;
     });