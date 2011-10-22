<?php

    /* Codeine
     * @author BreathLess
     * @description: Zend Style Routing
     * @package Codeine
     * @version 6.0
     * @date 27.08.11
     * @time 6:38
     */

    self::Fn('Route', function ($Call)
    {
        if (is_string($Call['Value']) && mb_strpos($Call['Value'], '/') !== false)
        {
            $NewCall = array();

            $Slices = explode('/', trim($Call['Value'],'/'));
            $Size = sizeof($Slices);

            if (($Size % 2) !== 0)
                $Slices[$Size] = true;

            for ($ic = 0; $ic < $Size; $ic += 2)
                $NewCall[$Slices[$ic]] = $Slices[$ic+1];

            return $NewCall;
        }
        else
            return $Call;
    });
