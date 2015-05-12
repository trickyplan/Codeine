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
                if (isset($Node['Type']) && F::Dot($Call['Data'], $Name))
                    $Call['Data'] = F::Dot($Call['Data'], $Name,
                        F::Run ('Data.Type.'.$Node['Type'],
                            'Write', $Call, $Node, 
                            [
                                'Entity' => $Call['Entity'],
                                'Name' => $Name,
                                'Node' => $Node,
                                'Data' => $Call['Data'],
                                'Purpose' => isset($Call['Purpose'])? $Call['Purpose']: '',
                                'Value' => F::Dot($Call['Data'], $Name)
                            ]));

        return $Call;
    });

    setFn('Read', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
            if (isset($Node['Type']) && F::Dot($Call['Data'], $Name) !== null or isset($Node['External']))
                $Call['Data'] = F::Dot($Call['Data'], $Name, F::Run ('Data.Type.'.$Node['Type'],
                        'Read', $Call,
                        [
                            'Entity' => $Call['Entity'],
                            'Name' => $Name,
                            'Node' => $Node,
                            'Data' => $Call['Data'],
                            'Purpose' => isset($Call['Purpose'])? $Call['Purpose']: '',
                            'Value' => F::Dot($Call['Data'], $Name)
                        ]));

        return $Call;
    });