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
            if (isset($Node['Filter']) && isset($Call['Data'][$Name]))
                foreach ($Node['Filter'] as $Filter)
                    $Call['Data'][$Name] = F::Live($Filter, ['Value' => $Call['Data'][$Name]]);

        return $Call;
    });