<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Default value support 
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
            if (empty(F::Dot($Call['Data'], $Name)))
                $Call['Data'] = F::Dot($Call['Data'], $Name, null);

        return $Call;
    });