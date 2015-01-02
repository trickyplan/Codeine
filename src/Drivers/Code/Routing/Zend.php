<?php

    /* Codeine
     * @author BreathLess
     * @description: Zend Style Routing
     * @package Codeine
     * @version 8.x
     * @date 27.28.11
     * @time 6:38
     */

    setFn('Route', function ($Call)
    {
        if (is_string($Call['Run']) && mb_strpos($Call['Run'], '/') !== false)
        {
            $NewCall = [];

            $Slices = explode('/', trim($Call['Run'],'/'));
            $Size = sizeof($Slices);

            if (($Size % 2) !== 0)
                $Slices[$Size] = true;

            for ($ic = 0; $ic < $Size; $ic += 2)
                $NewCall[$Slices[$ic]] = urldecode($Slices[$ic+1]);

            $Call['Run'] = $NewCall;
        }

        return $Call;
    });
