<?php

    /* Codeine
     * @author BreathLess
     * @description Data.Types.Input
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Write', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
            if (isset($Node['Type']) && isset($Call['Data'][$Name]))
            {
                $Call['Data'][$Name] =
                    F::Run('Data.Type.'.$Node['Type'], 'Write',
                        array('Entity' => $Call['Entity'],
                            'Name' => $Name,
                            'Node' => $Node,
                            'Data' => $Call['Data'],
                            'Value' => $Call['Data'][$Name]));

                if (null === $Call['Data'][$Name])
                    unset($Call['Data'][$Name]);
            }

        // TODO Multiwrite
        return $Call;
    });

    self::setFn('Read', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
            if (isset($Node['Type']))
                foreach ($Call['Data'] as &$Element)
                    $Element[$Name] =
                        F::Run('Data.Type.'.$Node['Type'], 'Read',
                            array('Entity' => $Call['Entity'],
                                  'Name' => $Name,
                                  'Node' => $Node,
                                  'Data' => $Element,
                                  'Value' => isset($Element[$Name])? $Element[$Name]: null));

        return $Call;
    });