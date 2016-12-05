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
        {
            foreach ($Call['Nodes'] as $Name => $Node)
                if (is_array($Call['Data']))
                    foreach ($Call['Data'] as &$Data)
                        if (empty(F::Dot($Data, $Name)))
                            $Data = F::Dot($Data, $Name, null);
        }

        return $Call;
    });