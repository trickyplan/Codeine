<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.2
     * @issue 30
     */

    self::setFn('Parse', function ($Call)
    {
        foreach ($Call['Parsers'] as $Parser)
            $Call['Value'] = F::Live($Parser, $Call);

        return $Call['Value'];
    });

    self::setFn('Load', function ($Call)
    {
        return F::Run('IO', 'Read', $Call,
            array (
                  'Storage' => 'Layout',
                  'Where'   => array ('ID' => array (
                      $Call['ID'] . (isset($Call['Context']) ? '.' . $Call['Context'] : '') . (isset($Call['Extension']) ? $Call['Extension'] : '.html'),
                      $Call['ID'] . '.html'))
            ));
    });

    self::setFn('LoadParsed', function ($Call)
    {
        return F::Run('View', 'Parse',
            array (
                  'Data'  => $Call['Data'],
                  'Value' => F::Run('View', 'Load', $Call)
            ));
    });

    self::setFn('Render', function ($Call)
    {
        $Call = F::Hook('beforeRender', $Call);

        $Call = F::Live (F::Live($Call['Rendering'], $Call), $Call);

        return F::Hook('afterRender', $Call);
    });

    self::setFn('RenderSlice', function ($Call)
    {
        $Call = F::Hook('beforeRenderSlice', $Call);

        $Call = F::Live (F::Live($Call['Rendering'], $Call), $Call);

        return F::Hook('afterRenderSlice', $Call);
    });

    self::setFn('Asset.Route', function ($Call)
    {
        if (strpos($Call['Value'], ':') !== false)
            return explode(':', $Call['Value']);
        else
            return array ($Call['Value'], $Call['Value']);
    });