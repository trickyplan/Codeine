<?php

    /* Codeine
     * @author BreathLess
     * @description Data.Types.Input
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Read', function ($Call)
    {
        if (empty($Call['Data']))
            return $Call;

        foreach ($Call['Nodes'] as $Name => $Node)
            if (isset($Node['Type']))
                foreach($Call['Data'] as $IX => $Element)
                    $Call['Data'][$IX][$Name] = F::Run('Entity.Nodes.Type.'.$Node['Type'], 'Read', $Call,
                        array('Node' => $Name,
                              'Value' => isset($Element[$Name])? $Element[$Name]: null));

        return $Call;
    });

    self::setFn('Write', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
            if (isset($Node['Type']))
                    $Call['Data'][$Name] = F::Run('Entity.Nodes.Type.'.$Node['Type'], 'Write', $Call,
                        array('Node' => $Name,
                              'Value' => $Call['Data'][$Name]));
        return $Call;
    });