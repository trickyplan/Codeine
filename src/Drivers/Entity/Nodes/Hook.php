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
                if (isset($Node['Hooks']) && isset($Node['Hooks'][$Call['On']]))
                {
                    if (is_array($Call['Data']))
                        foreach ($Call['Data'] as &$Element)
                        {
                            if (isset($Node['User Override']) and $Node['User Override'] and !empty($Element[$Name]))
                                F::Log('Node '.$Name.' overriden by user.', LOG_INFO);
                            else
                            {
                                $Element[$Name] = F::Live($Node['Hooks'][$Call['On']],
                                                   [
                                                       'Entity' => $Call['Entity'],
                                                       'Name' => $Name,
                                                       'Nodes' => $Call['Nodes'],
                                                       'Data' => $Element
                                                   ]);

                                if (is_array($Element[$Name]))
                                    F::Log('Node '.$Name.' executed as '.json_encode($Element[$Name]) , LOG_INFO);
                                else
                                    F::Log('Node '.$Name.' executed as '.$Element[$Name], LOG_INFO);
                            }
                        }
                }

        return $Call;
    });