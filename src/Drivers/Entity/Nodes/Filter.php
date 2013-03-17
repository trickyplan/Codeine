<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        foreach ($Call['Data'] as $Element)
            foreach ($Call['Nodes'] as $Name => $Node)
                if (isset($Node['Filter']) && isset($Element[$Name]))
                    foreach ($Node['Filter'] as $Filter)
                        $Element[$Name] = F::Live($Filter, ['Value' => $Element[$Name]]);

        return $Call;
    });