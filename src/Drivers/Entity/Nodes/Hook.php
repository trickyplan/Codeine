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
                    if (isset($Call['Data']) && ((array) $Call['Data'] === $Call['Data']))
                        foreach ($Call['Data'] as $IX => $Element)
                        {
                            if (isset($Node['User Override'])
                                && $Node['User Override']
                                && null != (F::Dot($Element, $Name))
                               )
                                F::Log('Node *'.$Name.'* overriden by user with *'.F::Dot($Element, $Name).'*', LOG_INFO);
                            else
                            {
                                $Element = F::Dot($Element, $Name, F::Live($Node['Hooks'][$Call['On']], $Call,
                                                   [
                                                       'Name' => $Name,
                                                       'Data' => $Element
                                                   ]));

                                if (is_array(F::Dot($Element, $Name)))
                                    F::Log('Node *'.$Name.'* executed as '.json_encode(F::Dot($Element, $Name)), LOG_INFO);
                                else
                                    F::Log('Node *'.$Name.'* executed as '.F::Dot($Element, $Name), LOG_INFO);
                            }

                            $Call['Data'][$IX] = $Element;
                        }
                }

        return $Call;
    });