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
        $IDs = array();

        $IDs[] = $Call['ID'];

        if (isset($Call['Context']) && !empty($Call['Context']))
            $IDs[] =  $Call['ID'].'.'.$Call['Context'];

        $Call['Scope'] = strtr($Call['Scope'], '.', '/');

        $Data =  F::Run('IO', 'Read', $Call,
            [
                  'Storage' => 'Layout',
                  'Where'   =>
                  [
                      'ID' => array_reverse($IDs)
                  ]
            ])[0];

        if ((null !== $Data) && isset($Call['View']['Layouts']['Debug']) && $Call['View']['Layouts']['Debug'])
            $Data = "\n".'<!-- '.$Call['Scope'].':'.$Call['ID'].' started -->'."\n".$Data."\n".'<!-- '.$Call['Scope'].':'.$Call['ID'].' ended -->';

        return $Data;
    });

    setFn('LoadParsed', function ($Call)
    {
        return F::Run('View', 'Parse', $Call,
            [
                  'Data'  => (isset($Call['Data'])? $Call['Data']: null),
                  'Value' => F::Run('View', 'Load', $Call)
            ]);
    });

    setFn('Render', function ($Call)
    {
        $Call = F::Hook('beforeRender', $Call);

        $Call = F::Live (F::Live($Call['Rendering'], $Call), $Call);

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