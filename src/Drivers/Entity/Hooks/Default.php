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
            if (isset($Node['Default']))
            {
                $Node['Default'] = F::Live($Node['Default']);
                
                if (F::Dot($Node, 'Empty as Default'))
                {
                    if (F::Dot($Call['Data'], $Name) == null)
                        $Call['Data'] = F::Dot($Call['Data'], $Name, $Node['Default']);
                }
                else
                {
                    if (F::Dot($Call['Data'], $Name) === null)
                        $Call['Data'] = F::Dot($Call['Data'], $Name, $Node['Default']);
                }
            }

        return $Call;
    });