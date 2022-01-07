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

        if (isset($Call['ID']))
        {
            $IDs = [$Call['ID']];

            if (isset($Call['Context']) && !empty($Call['Context']))
                $IDs[] =  $Call['ID'].'.'.$Call['Context'];

            $IDs = array_reverse($IDs);

            if (isset($Call['Scope']))
                $Call['Scope'] = strtr($Call['Scope'], '.', '/');

            $Call['Value'] = F::Run('IO', 'Read',
                [
                    'Scope'   => isset($Call['Scope'])? $Call['Scope']: null,
                    'Storage' => 'Layout',
                    'Where'   =>
                        [
                            'ID' => $IDs
                        ]
                ]);

            if (isset($Call['Data']) && ($Call['Data'] !== (array) $Call['Data']))
                $Call['Data'] = ['Value' => $Call['Data']];


            // Different logic from IO One
            if ($Call['Value'] === null)
                ;
            else
                $Call['Value'] = array_shift($Call['Value']);

            $Call = F::Hook('afterViewLoad', $Call);
        }
        else
            $Call['Value'] = 'NO ID';

        return $Call['Value'];
    });

    setFn('Render', function ($Call)
    {
        $Call = F::Hook('beforeRender', $Call);

        $Call['Environment'] = self::$_Environment;

        if (isset($Call['View']['Renderer']))
        {
            $Renderer = $Call['View']['Renderer']['Service'];
            F::Log('Start '.$Renderer.' Rendering', LOG_INFO);
                $Call = F::Live ($Call['View']['Renderer'], $Call);
            F::Log('Finish '.$Renderer.' Rendering', LOG_INFO);
        }

        return F::Hook('afterRender', $Call);
    });

    setFn('Asset.Route', function ($Call)
    {
        if (str_contains($Call['Value'], ':'))
        {
            list($Scope, $ID) = explode(':', $Call['Value']);

            if (isset($Call['Scope']) && $Scope != '')
                $Scope .= '/'.$Call['Scope'];
        }
        else
        {
            $Scope = $Call['Value'];
            $ID = $Scope;
            if (isset($Call['Scope']) && $Scope != '')
                $Scope .= '/'.$Call['Scope'];
        }

        return [$Scope, $ID];
    });

    setFn('Add', function ($Call)
    {
        $Call['Output'][$Call['Place']][] = $Call['Element'];

        return $Call;
    });