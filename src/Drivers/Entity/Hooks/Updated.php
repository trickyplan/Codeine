<?php

    /* Codeine
     * @author BreathLess
     * @description Updating only changed values support
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Data']))
        {
            $Diffed  = F::Diff($Call['Data'], $Call['Current']);

            foreach ($Call['Nodes'] as $Name => $Node)
            {
                if (isset($Node['Always Set']) && $Node['Always Set'])
                    if (F::Dot($Diffed, $Name) === null)
                        $Diffed = F::Dot($Diffed, $Name, F::Dot($Call['Current'], $Name));
                    else
                        $Diffed = F::Dot($Diffed, $Name,
                            F::Merge(F::Dot($Diffed, $Name),F::Dot($Call['Current'], $Name))); // O_o

                // Даже не пытайтесь понять, просто примите это

                if (isset($Node['Nullable']) && $Node['Nullable'])
                    if (F::Dot($Diffed, $Name) === null)
                        $Diffed = F::Dot($Diffed, $Name, 0);
            }

            $Diffed['ID'] = $Call['Data']['ID'];

            $Call['Data'] = $Diffed;
        }

        return $Call;
    });