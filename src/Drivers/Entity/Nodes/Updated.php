<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Data']))
            foreach ($Call['Data'] as $IX => &$Element)
                foreach ($Call['Nodes'] as $Name => $Node)
                {
                    $New = F::Live(F::Dot($Element, $Name));
                    $Current = F::Live(F::Dot($Call['Current'][$IX], $Name));

                    if (strpos($Name, '.') == false)
                    {
                        if (!isset($Node['Nullable']) || !$Node['Nullable'])
                        {
                            if (($New === null) || ($New == $Current))
                                $Element = F::Dot($Element, $Name, null);
                        }
                        else
                        {
                            if ($New == $Current)
                                $Element = F::Dot($Element, $Name, null);
                            elseif ($New === null)
                                $Element= F::Dot($Element, $Name, 0);
                        }
                    }
                }

        return $Call;
    });