<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Run', function ($Call)
     {
         if ($Hooks = F::Dot($Call, 'Hooks.'.$Call['On']))
             foreach ($Hooks as $Name => $Hook)
                 $Call = F::Run($Hook['Service'], $Hook['Method'], $Call, isset($Hook['Call'])? $Hook['Call']: array());

         return $Call;
     });