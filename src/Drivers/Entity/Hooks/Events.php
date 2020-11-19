<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Default value support 
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        // Future Replacement for Live Nodes
        if (isset($Call['Nodes']))
            foreach ($Call['Nodes'] as $Name => $Node)
            {
                if (F::Dot($Call, 'Current.'.$Name) !== F::Dot($Call, 'Data.'.$Name))
                    $Call = F::Hook('Node.'.$Call['Event'].'.'.$Name, $Call);
            }

        return $Call;
    });