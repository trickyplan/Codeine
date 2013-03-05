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
            $New = F::Live(F::Dot($Call['Data'], $Name));
            $Current = F::Live(F::Dot($Call['Current'], $Name));

            if (strpos($Name, '.') == false)
            {
                if (!isset($Node['Nullable']) || !$Node['Nullable'])
                {
                    if (($New === null) || ($New == $Current))
                        $Call['Data'] = F::Dot($Call['Data'], $Name, null);
                }
                else
                {
                    if ($New == $Current)
                        $Call['Data'] = F::Dot($Call['Data'], $Name, null);
                    elseif ($New === null)
                        $Call['Data'] = F::Dot($Call['Data'], $Name, 0);
                }
            }
        }

        return $Call;
    });