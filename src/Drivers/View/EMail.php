<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 8.x
     */

    setFn('Render', function ($Call)
    {
        $Call['Context'] = 'email';
        $Call = F::Hook('beforePipeline', $Call);

        $Call = F::Run (null, 'Pipeline', $Call, ['Renderer' => 'View.HTML']); // Pipelining

        $Call = F::Hook('afterPipeline', $Call);

        return $Call;
    });

    setFn('Pipeline', function ($Call)
    {
        if (isset($Call['Layouts']))
        {
            foreach ($Call['Layouts'] as $Layout) // FIXME I'm fat
                if (($Sublayout =  F::Run('View', 'Load', $Layout)) !== null)
                    $Call['Layout'] = str_replace('<place>Content</place>', $Sublayout, $Call['Layout']);
        }

        if (preg_match_all('@<call>(.*)<\/call>@SsUu', $Call['Layout'], $Pocket)) // TODO Вынести в хук
            foreach ($Pocket[0] as $IX => $Match)
            {
                $Matched = F::Dot($Call, $Pocket[1][$IX]);

                if (($Matched === false) || ($Matched === 0))
                        $Matched = '0';

                $Call['Layout'] = str_replace($Match, $Matched, $Call['Layout']);
            }

        if (preg_match_all('@<place>(.*)<\/place>@SsUu', $Call['Layout'], $Places))
        {
            if (isset($Call['Output']))
            {
                if (is_array($Call['Output']))
                    foreach ($Call['Output'] as $Place => $Widgets)
                        foreach ($Widgets as $Key => $Widget)
                        {
                            if(is_array($Widget))
                                $Call['Output'][$Place][$Key] = F::Run($Call['View']['Renderer'] . '.Element.' . $Widget['Type'],
                                    'Make', $Widget, ['Session' => $Call['Session']]); // FIXME FIXME FIXME
                            else
                                $Call['Output'][$Place][$Key] = $Widget;
                        }
                // TODO Normal caching
            }

            if (isset($Call['Output']) && is_array($Call['Output']))
                foreach ($Call['Output'] as $Place => $Widgets)
                    $Call['Layout'] = str_replace('<place>' . $Place . '</place>', implode('', $Widgets), $Call['Layout']);
        }

        if (preg_match_all('@<call>(.*)<\/call>@SsUu', $Call['Layout'], $Pocket)) // TODO Вынести в хук
            foreach ($Pocket[0] as $IX => $Match)
            {
                $Matched = F::Dot($Call, $Pocket[1][$IX]);

                if (($Matched === false) || ($Matched === 0))
                        $Matched = '0';

                $Call['Layout'] = str_replace($Match, $Matched, $Call['Layout']);
            }

        $Call['Output'] = $Call['Layout'];
        return $Call;
    });