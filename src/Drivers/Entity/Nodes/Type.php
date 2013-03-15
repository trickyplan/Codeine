<?php

    /* Codeine
     * @author BreathLess
     * @description Data.Types.Input
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Type']) && (F::Dot($Call['Data'], $Name) !== null))
            {
                $Call['Data'] = F::Dot($Call['Data'], $Name,
                    F::Run('Data.Type.'.$Node['Type'], 'Write', $Node, [
                        'Entity' => $Call['Entity'],
                        'Name' => $Name,
                        'Node' => $Node,
                        'Purpose' => $Call['Purpose'],
                        'Data' => $Call['Data'],
                        'Current' => isset($Call['Current'])? $Call['Current']: null,
                        'Value' => F::Dot($Call['Data'], $Name)
                    ])); // FIXME SHIT!

            }
        }

        // TODO Multiwrite
        return $Call;
    });

    setFn('Read', function ($Call)
    {
        if (isset($Call['Data']))
            foreach ($Call['Nodes'] as $Name => $Node)
                if (isset($Node['Type']))
                    foreach ($Call['Data'] as &$Element)
                    {
                        if ((F::Dot($Element, $Name) !== null) or (isset($Node['External'])))
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