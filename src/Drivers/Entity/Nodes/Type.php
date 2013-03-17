<?php

    /* Codeine
     * @author BreathLess
     * @description Data.Types.Input
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        if (isset($Call['Data']))
            foreach ($Call['Nodes'] as $Name => $Node)
                if (isset($Node['Type']))
                    foreach ($Call['Data'] as &$Element)
                    {
                        if (F::Dot($Element, $Name))
                            $Element = F::Dot($Element, $Name, F::Run ('Data.Type.'.$Node['Type'],
                                    'Read',
                                    [
                                        'Entity' => $Call['Entity'],
                                        'Name' => $Name,
                                        'Node' => $Node,
                                        'Data' => $Element,
                                        'Value' => F::Dot($Element, $Name)
                                    ]));
                    }

        return $Call;
    });

    setFn('Read', function ($Call)
    {
        if (isset($Call['Data']))
            foreach ($Call['Nodes'] as $Name => $Node)
                if (isset($Node['Type']))
                    foreach ($Call['Data'] as &$Element)
                    {
                        if (F::Dot($Element, $Name) !== null or isset($Node['External']))
                            $Element = F::Dot($Element, $Name, F::Run ('Data.Type.'.$Node['Type'],
                                    'Read',
                                    [
                                        'Entity' => $Call['Entity'],
                                        'Name' => $Name,
                                        'Node' => $Node,
                                        'Data' => $Element,
                                        'Value' => F::Dot($Element, $Name)
                                    ]));
                    }
        return $Call;
    });