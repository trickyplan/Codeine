<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Data.Types.Input
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        if (isset($Call['Dry']))
            ;
        else
            foreach ($Call['Nodes'] as $Name => $Node)
                if (isset($Node['Type']) && F::Dot($Call['Data'], $Name) !== null)
                    $Call['Data'] = F::Dot($Call['Data'], $Name,
                        F::Run ('Data.Type.'.$Node['Type'],
                            'Write', $Call, $Node, 
                            [
                                'Entity' => $Call['Entity'],
                                'Name' => $Name,
                                'Node' => $Node,
                                'Data' => $Call['Data'],
                                'Purpose' => isset($Call['Purpose'])? $Call['Purpose']: '',
                                'Value' => F::Dot($Call, 'Data.'.$Name),
                                'Old' => F::Dot($Call, 'Current.'.$Name)
                            ]));

        return $Call;
    });

    setFn('Read', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            $Value = F::Dot($Call['Data'], $Name);
            if (isset($Node['Type']) &&
                    ($Value !== null or isset($Node['External'])
                ))
                $Call['Data'] = F::Dot($Call['Data'], $Name, F::Run ('Data.Type.'.$Node['Type'],
                    'Read', $Call,
                    [
                        'Entity' => $Call['Entity'],
                        'Name' => $Name,
                        'Node' => $Node,
                        'Data' => $Call['Data'],
                        'Purpose' => isset($Call['Purpose'])? $Call['Purpose']: '',
                        'Value' => $Value
                    ]));
        }

        return $Call;
    });