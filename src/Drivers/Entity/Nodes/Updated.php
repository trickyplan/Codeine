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
            $New = F::Dot($Call['Data'], $Name);

            if (!isset($Node['Nullable']) || !$Node['Nullable'])
            {
                if (($New === null) || ($New == F::Dot($Call['Current'], $Name)))
                    $Call['Data'] = F::Dot($Call['Data'], $Name, null);
            }
            else
            {
                if ($New == F::Dot($Call['Current'], $Name))
                    unset($Call['Data'][$Name]);
                elseif ($New === null)
                    $Call['Data'][$Name] = 0;
            }
        }

        return $Call;
    });