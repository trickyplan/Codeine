<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Run', function ($Call)
     {
         if (isset($Call['Hooks'][$Call['On']]))
             foreach ($Call['Hooks'][$Call['On']] as $Name => $Hook)
                 $Call = F::Run($Call, $Hook);

         return $Call;
     });