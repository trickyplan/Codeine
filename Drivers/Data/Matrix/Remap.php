<?php

    /* Codeine
     * @author BreathLess
     * @description:
     * @package Codeine
     * @version 6.0
     * @date 03.04.11
     * @time 14:30
     */

    self::Fn('Keys', function ($Call)
    {
        foreach ($Call['Map'] as $From => $To)
        {
            $Call['Value'][$To] = $Call['Value'][$From];
            unset($Call['Value'][$From]);
        }

        return $Call['Value'];
    });
