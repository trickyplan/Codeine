<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Default']))
            {
                $Node['Default'] = F::Live($Node['Default']);

                if (F::Dot($Call['Data'], $Name) === null)
                    $Call['Data'] = F::Dot($Call['Data'],$Name,$Node['Default']); // FIXME Add flag

            }
        }

        return $Call;
    });