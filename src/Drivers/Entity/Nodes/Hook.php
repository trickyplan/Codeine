<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
       if (isset($Call['Nodes']))
            foreach ($Call['Nodes'] as $Name => $Node)
            {
                if (isset($Node['Hooks']))
                    if(isset($Node['Hooks'][$Call['On']]))
                    {
                        // Multiread
                        if (isset($Call['Purpose']) && ($Call['Purpose'] == 'Read'))
                        {
                            if (isset($Call['Data']))
                                foreach ($Call['Data'] as &$Data)
                                    $Data[$Name] = F::Live($Node['Hooks'][$Call['On']], ['Entity' => $Call['Entity'], 'Data' => $Data]);
                        }
                        else
                            $Call['Data'][$Name] = F::Live($Node['Hooks'][$Call['On']], $Call);
                    }
            }

        return $Call;
    });