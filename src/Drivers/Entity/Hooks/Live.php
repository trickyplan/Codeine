<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        // Если модель определена
        if (isset($Call['Nodes']))
            foreach ($Call['Nodes'] as $Name => $Node)
            {
                // Если частичная загрузка, то нужно проверить, нужен ли нам этот хук.
                if (isset($Call['Partial']) && !in_array($Name, $Call['Fields']))
                    continue;

                // Если у ноды определён нужный хук
                if (isset($Node['Hooks']) && isset($Node['Hooks'][$Call['On']]))
                {
                    if (isset($Call['Data']) && ((array) $Call['Data'] === $Call['Data']))
                    {
                        if (isset($Node['User Override'])
                            && $Node['User Override']
                            && null != (F::Dot($Call['Data'], $Name))
                           )
                            F::Log('Node *'.$Name.'* overriden by user with *'.F::Dot($Call['Data'], $Name).'*', LOG_INFO);
                        else
                        {
                            $Call['Data'] =
                                F::Dot($Call['Data'], $Name,
                                    F::Live($Node['Hooks'][$Call['On']],
                                        $Call,
                                        [
                                           'Name' => $Name,
                                           'Data' => $Call['Data']
                                        ]));

                            if (is_array(F::Dot($Call['Data'], $Name)))
                                F::Log('Node *'.$Name.'* executed as '.json_encode(
                                    F::Dot($Call['Data'], $Name),
                                    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOG_INFO);
                            else
                                F::Log('Node *'.$Name.'* executed as '.
                                F::Dot($Call['Data'], $Name)
                                , LOG_INFO);
                        }
                    }
                }
            }

        return $Call;
    });