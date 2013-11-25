<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Nullable']) && $Node['Nullable'])
                if (F::Dot($Call['Data'], $Name) === null)
                    $Call['Data'] = F::Dot($Call['Data'],$Name,$Node['Default']);
        }

        return $Call;
    });