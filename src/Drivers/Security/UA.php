<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Filter', function ($Call)
    {
        // TODO Realize "Filter" function
        $Call['UA'] = F::Live($Call['UA']);

        if (isset($Call['Bad UA']))
            foreach ($Call['Bad UA'] as $Bad)
                if (preg_match('/'.$Bad.'/', $Call['UA']))
                {
                    $Call = F::Hook('BadUADetected', $Call);
                    $Call['Skip Run'] = true;
                    break;
                }

        return $Call;
    });