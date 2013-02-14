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


        if ($Places = F::Run('Text.Regex', 'All', ['Pattern' => '<call>(.+?)<\/call>', 'Value' => $Call['Layout']]))
            foreach ($Places[0] as $IX => $Match)
            {
                $Matched = F::Live(F::Dot($Call, $Places[1][$IX]));

                if (($Matched === false) || ($Matched === 0))
                        $Matched = '0';

                $Call['Layout'] = str_replace($Match, $Matched, $Call['Layout']);
            }

        if ($Places = F::Run('Text.Regex', 'All', ['Pattern' => '<place>(.+?)<\/place>', 'Value' => $Call['Layout']]))
        {
            if (isset($Call['Output']))
            {
                if (is_array($Call['Output']))
                    foreach ($Call['Output'] as $Place => $Widgets)
                        if(is_array($Widgets))
                        foreach ($Widgets as $Key => $Widget)
                        {
                            if(is_array($Widget))
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

        if ($Places = F::Run('Text.Regex', 'All', ['Pattern' => '<call>(.+?)<\/call>', 'Value' => $Call['Layout']]))
            foreach ($Places[0] as $IX => $Match)
            {
                $Matched = F::Live(F::Dot($Call, $Places[1][$IX]));

                if (($Matched === false) || ($Matched === 0))
                        $Matched = '0';

                $Call['Layout'] = str_replace($Match, $Matched, $Call['Layout']);
            }

        $Call['Output'] = $Call['Layout'];

        $Call = F::Hook('afterPipeline', $Call);


        return $Call;
    });