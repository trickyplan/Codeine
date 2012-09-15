<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeCollect', $Call);

            foreach ($Call['Monitor']['Bundles'] as $Bundle => $Options)
                if (time() % $Options['Period'] == 0)
                    $Call['Status'][$Bundle] = F::Run($Bundle.'.Monitor', 'Do', $Call);

        $Call = F::Hook('afterCollect', $Call);

        return $Call;
    });