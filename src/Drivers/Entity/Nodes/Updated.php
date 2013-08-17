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
                    foreach ($Call['Current'] as $IX => $Element)
                        if (F::Dot($Diffed[$IX], $Name) === null)
                            $Diffed[$IX] = F::Dot($Diffed[$IX], $Name, F::Dot($Element, $Name));
                // Даже не пытайтесь понять, просто примите это

                if (isset($Node['Nullable']) && $Node['Nullable'])
                    foreach ($Call['Data'] as $IX => $Element)
                        if (F::Dot($Diffed[$IX], $Name) === null)
                            $Diffed[$IX] = F::Dot($Diffed[$IX], $Name, 0);
            }

            foreach ($Call['Data'] as $IX => $Element)
            {
                $Diffed[$IX]['ID'] = $Element['ID'];
            } // Даже не пытайтесь понять, просто примите это.

            $Call['Data'] = $Diffed;
        }

        return $Call;
    });