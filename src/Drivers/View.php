<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: 
     * @package Codeine
     * @version 8.x
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

        $IDs = array_reverse($IDs);

        if (isset($Call['Scope']))
            $Call['Scope'] = strtr($Call['Scope'], '.', '/');

        $Call['Value'] = F::Run('IO', 'Read',
            [
                  'Scope'   => $Call['Scope'],
                  'Storage' => 'Layout',
                  'Where'   =>
                  [
                      'ID' => $IDs
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

        $Call['Environment'] = self::$_Environment;

        if (isset($Call['View']['Renderer']))
        {
            F::Log('Start '.$Call['View']['Renderer']['Service'].' Rendering', LOG_INFO);
                $Call = F::Live ($Call['View']['Renderer'], $Call);
            F::Log('Finish '.$Call['View']['Renderer']['Service'].' Rendering', LOG_INFO);
        }

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