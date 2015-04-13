<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 8.x
     */

    setFn('Render', function ($Call)
    {
        $Call = F::Hook('beforeHTMLRender', $Call);

            $Call = F::Apply(null,'Pipeline', $Call); // Pipelining

        $Call = F::Hook('afterHTMLRender', $Call);

        return $Call;
    });

    setFn('Pipeline', function ($Call)
    {
        $Call = F::Hook('beforeHTMLPipeline', $Call);

        $Places = F::Run('Text.Regex', 'All',
            [
                'Pattern' => $Call['Place Pattern'],
                'Value' => $Call['Layout']
            ]);

        if ($Places && isset($Call['Output']) && is_array($Call['Output'])) //  Если есть посадочные места.
        {
            foreach ($Call['Output'] as $Place => $Widgets)
                if (is_array($Widgets))
                    foreach ($Widgets as $Key => $Widget)
                    {
                        if (is_array($Widget))
                        {
                            $Call['Output'][$Place][$Key] = F::Run ('View', 'Load',
                                [
                                    'Scope' => (isset($Widget['Widget Set'])? $Widget['Widget Set']: $Call['View']['HTML']['Widget Set']).'/Widgets',
                                    'ID'    => (isset($Widget['Widget Template'])?
                                                $Widget['Widget Template']
                                                : strtr($Widget['Type'],'.', '/')),
                                    'Data'  =>
                                        F::Run(
                                            $Call['View']['Renderer']['Service']
                                            .'.Widget.'
                                            .$Widget['Type'],
                                            'Make',
                                            $Call,
                                            $Widget)
                                ]);
                        }
                        else
                            $Call['Output'][$Place][$Key] = $Widget;
                    }

            foreach ($Call['Output'] as $Place => $Widgets)
                if (is_array($Widgets))
                    $Call['Layout'] = str_replace('<place>' . $Place . '</place>',
                        implode(PHP_EOL, $Widgets), $Call['Layout']);
        }

        $Call = F::Apply(null, 'Parse Call', $Call);

        $Call['Output'] = $Call['Layout'];

        unset($Call['Layout']);
        $Call = F::Hook('afterHTMLPipeline', $Call);

        return $Call;
    });

    setFn('Parse Call', function ($Call)
    {
        if (preg_match_all('@<call>(.*)<\/call>@SsUu', $Call['Layout'], $Places))
            foreach ($Places[0] as $IX => $Match)
            {
                $Places[1][$IX] = F::Live(F::Dot($Call, $Places[1][$IX]));

                if (($Places[1][$IX] === false) || ($Places[1][$IX]=== 0))
                    $Places[1][$IX] = '0';

                if (is_array($Places[1][$IX]))
                    $Places[1][$IX] = implode('', $Places[1][$IX]);
            }

        $Call['Layout'] = str_replace($Places[0], $Places[1], $Call['Layout']);

        return $Call;
    });