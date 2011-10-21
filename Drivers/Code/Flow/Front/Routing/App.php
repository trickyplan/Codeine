<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 6:17
     */

    self::Fn('Route', function ($Call)
    {
        if (is_string($Call['Value']))
        {
            $Slices = explode('/',$Call['Value']);
            if ($Slices[1] == 'Apps')
            {
                return array(
                    '_N' => 'Apps.'.$Slices[2],
                    '_F' => $Slices[3]
                );
            }

        }

        return null;
    });
