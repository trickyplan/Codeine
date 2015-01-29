<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Default value support 
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        $Data = [];

        foreach ($Call['Nodes'] as $Name => $Node)
            if (F::Dot($Call['Nodes'], $Name) !== null)
                $Data = F::Dot($Data, $Name, F::Dot($Call['Data'], $Name));

        $Call['Data'] = $Data;

        return $Call;
    });