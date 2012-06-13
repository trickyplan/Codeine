<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Process', function ($Call)
    {
        if (isset($Call['Data']))
            foreach ($Call['Data'] as $Name => $Value)
                if (!isset($Call['Nodes'][$Name]))
                    unset($Call['Data'][$Name]);

        return $Call;
    });