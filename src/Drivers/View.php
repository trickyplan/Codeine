<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.x
     * @issue 30
     */

    setFn('Parse', function ($Call)
    {
        foreach ($Call['Parsers'] as $Parser)
            $Call['Value'] = F::Live($Parser, $Call);

        return $Call['Value'];
    });

    setFn('Load', function ($Call)
    {
        $Call = F::Hook('beforeViewLoad', $Call);

        $IDs = [$Call['ID']];

        if (isset($Call['Context']) && !empty($Call['Context']))
            $IDs[] =  $Call['ID'].'.'.$Call['Context'];

        $Call['Scope'] = strtr($Call['Scope'], '.', '/');

        $Call['Value'] =  F::Run('IO', 'Read', $Call,
            [
                  'Storage' => 'Layout',
                  'Where!'   =>
                  [
                      'ID' => array_reverse($IDs)
                  ]
            ])[0];

        if (isset($Call['Data']) && ($Call['Data'] !== (array) $Call['Data']))
            $Call['Data'] = ['Value' => $Call['Data']];

        if ($Call['Value'] !== null)
            $Call = F::Hook('afterViewLoad', $Call);

        return $Call['Value'];
    });

    setFn('Render', function ($Call)
    {
        $Call = F::Hook('beforeRender', $Call);

        $Call = F::Live ($Call['Renderer'], $Call);

        return F::Hook('afterRender', $Call);
    });

    setFn('Asset.Route', function ($Call)
    {
        if (strpos($Call['Value'], ':') !== false)
            return explode(':', $Call['Value']);
        else
            return array ($Call['Value'], $Call['Value']);
    });

    setFn('Add', function ($Call)
    {
        $Call['Output'][$Call['Place']][] = $Call['Element'];

        return $Call;
    });