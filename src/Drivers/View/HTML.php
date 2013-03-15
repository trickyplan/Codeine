<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.x
     */

    setFn('Render', function ($Call)
    {
        $Call = F::Run (null,'Pipeline', $Call, array('Renderer' => 'View.HTML')); // Pipelining

        return $Call;
    });

    setFn('Pipeline', function ($Call)
    {
        if (isset($Call['Layouts']))
        {
            foreach ($Call['Layouts'] as $Layout) // FIXME I'm fat
                if (($Sublayout =  F::Run('View', 'LoadParsed', $Layout)) !== null)
                    $Call['Layout'] = str_replace('<place>Content</place>', $Sublayout, $Call['Layout']);
        }

        $Call = F::Hook('beforePipeline', $Call);

        $Call = F::Run(null, 'Parse Call', $Call);

        if ($Places = F::Run('Text.Regex', 'All', ['Pattern' => '<place>(.+?)<\/place>', 'Value' => $Call['Layout']]))
        {
            if (isset($Call['Output']))
            {
                if (is_array($Call['Output']))
                    foreach ($Call['Output'] as $Place => $Widgets)
                        if (is_array($Widgets))
                            foreach ($Widgets as $Key => $Widget)
                            {
                                if (is_array($Widget))
                                    $Call['Output'][$Place][$Key]
                                        = F::Run($Call['Renderer'] . '.Element.' . $Widget['Type'], 'Make', $Widget, array('Session' => $Call['Session']));
                                        // FIXME FIXME FIXME
                                else
                                    $Call['Output'][$Place][$Key] = $Widget;
                            }
                // TODO Normal caching
            }

            if (isset($Call['Output']) && is_array($Call['Output']))
                foreach ($Call['Output'] as $Place => $Widgets)
                    if (is_array($Widgets))
                        $Call['Layout'] = str_replace('<place>' . $Place . '</place>', implode('', $Widgets), $Call['Layout']);
        }

        $Call = F::Run(null, 'Parse Call', $Call);

        $Call['Output'] = $Call['Layout'];

        $Call = F::Hook('afterPipeline', $Call);


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