<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Process', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (!isset($Node['Nullable']) && !$Node['Nullable'])
            {
                $New = F::Dot($Call['Data'], $Name);

                if (empty($New) || ($New === null) || ($New == F::Dot($Call['Current'], $Name)))
                    $Call['Data'] = F::Dot($Call['Data'], $Name, null);
            }
        }

        return $Call;
    });