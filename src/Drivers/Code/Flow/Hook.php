<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

     setFn('Run', function ($Call)
     {
         if (isset($Call['Hooks']))
             if ($Hooks = F::Dot($Call, 'Hooks.' . $Call['On']))
                 foreach ($Hooks as $Name => $Hook)
                     $Call = F::Apply($Hook['Service'], $Hook['Method'], $Call, isset($Hook['Call']) ? $Hook['Call'] : array ());

         return $Call;
     });