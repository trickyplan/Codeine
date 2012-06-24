<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Process', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            $Run = null;

            if (isset($Node[$Call['Purpose']]))
                $Run = $Node[$Call['Purpose']];
            elseif (isset($Node['Write']))
                $Run = $Node['Write'];

            if (null !== $Run)
            {
                if (!isset($Node['User Override']) || !isset($Call['Data'][$Name]))
                    $Call['Data'][$Name] = F::Live($Run, $Call);
            }
        }

        return $Call;
    });