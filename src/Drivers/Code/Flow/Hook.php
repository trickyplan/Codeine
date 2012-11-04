<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

     setFn('Run', function ($Call)
     {
         if (isset($Call['Hooks']))
             if ($Hooks = F::Dot($Call, 'Hooks.' . $Call['On']))
                 foreach ($Hooks as $Name => $Hook)
                     $Call = F::Run($Hook['Service'], $Hook['Method'], $Call, isset($Hook['Call']) ? $Hook['Call'] : array ());

         return $Call;
     });