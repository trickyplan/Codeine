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
        {
            foreach ($Call['Data'] as &$Element)
            {
                $Data = [];

                foreach ($Call['Nodes'] as $Name => $Node)
                    if (F::Dot($Call['Nodes'], $Name) !== null)
                        $Data = F::Dot($Data, $Name, F::Dot($Element, $Name));

                $Element = $Data;
            }
        }

        return $Call;
    });