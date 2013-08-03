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

                if (isset($Call['Data']))
                    foreach ($Call['Data'] as &$Element)
                        if (F::Dot($Element, $Name) === null)
                            $Element = F::Dot($Element,$Name,$Node['Default']); // FIXME Add flag
            }
        }
        return $Call;
    });