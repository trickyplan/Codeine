<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Default value support 
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Data']))
            foreach ($Call['Nodes'] as $Name => $Node)
            {
                $Value = F::Dot($Call['Data'], $Name);
                if ($Value === null and F::Dot($Node, 'Nullable') == true)
                    $Call['Data'] = F::Dot($Call['Data'], $Name, null);
            }

        return $Call;
    });