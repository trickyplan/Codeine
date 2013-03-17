<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Nodes']) && isset($Call['Data']))
            foreach ($Call['Nodes'] as $Name => $Node)
            {
                if (isset($Node['Hooks']) && isset($Node['Hooks'][$Call['On']]))
                {
                    foreach ($Call['Data'] as &$Element)
                    {
                        if (isset($Node['User Override']) and $Node['User Override'] and !empty($Element[$Name]))
                            F::Log('Node '.$Name.' overriden by user.', LOG_INFO);
                        else
                        {
                            $Element[$Name] = F::Live($Node['Hooks'][$Call['On']],
                                               [
                                                   'Entity' => $Call['Entity'],
                                                   'Nodes' => $Call['Nodes'],
                                                   'Data' => $Element
                                               ]);

                            F::Log('Node '.$Name.' executed.', LOG_INFO);
                        }
                    }
                }
            }

        return $Call;
    });