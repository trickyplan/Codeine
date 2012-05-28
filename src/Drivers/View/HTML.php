<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Render', function ($Call)
    {
       $Call = F::Run ('Code.Flow.Hook', 'Run', $Call, array('On' => 'beforePipeline'));      // JP beforeRender

           $Call = F::Run (null,'Pipeline', $Call, array('Renderer' => 'View.HTML')); // Pipelining

       $Call = F::Run ('Code.Flow.Hook', 'Run', $Call, array ('On' => 'afterPipeline'));      // JP afterRender

       return $Call;
    });

    self::setFn('Pipeline', function ($Call)
    {
        if (isset($Call['Layouts']))
            foreach ($Call['Layouts'] as $Layout) // FIXME I'm fat
                if (($Sublayout =  F::Run('View', 'LoadParsed', $Layout, array('Context' => $Call['Context']))) !== null)
                    $Call['Layout'] = str_replace('<place>Content</place>', $Sublayout, $Call['Layout']);

        if (preg_match_all('@<call>(.*)<\/call>@SsUu', $Call['Layout'], $Pocket)) // TODO Вынести в хук
            foreach ($Pocket[0] as $IX => $Match)
                $Call['Layout'] = str_replace($Match, F::Dot($Call, $Pocket[1][$IX]), $Call['Layout']);

        if (preg_match_all('@<place>(.*)<\/place>@SsUu', $Call['Layout'], $Places))
        {
            if (isset($Call['Output']))
            {
                if (is_array($Call['Output']))
                    foreach ($Call['Output'] as $Place => $Widgets)
                        foreach ($Widgets as $Key => $Widget)
                        {
                            if(is_array($Widget))
                                $Call['Output'][$Place][$Key] = F::Run($Call['Renderer'] . '.Element.' . $Widget['Type'], 'Make', $Widget);
                            else
                                $Call['Output'][$Place][$Key] = $Widget;
                        }
                // TODO Normal caching
            }

            if (!isset($Call['Output']['Content']))
                $Call['Output']['Content'] = array();

            foreach ($Call['Output'] as $Place => $Widgets)
                $Call['Layout'] = str_replace('<place>' . $Place . '</place>', implode('', $Widgets), $Call['Layout']);
        }

        if (preg_match_all('@<call>(.*)<\/call>@SsUu', $Call['Layout'], $Pocket)) // TODO Вынести в хук
            foreach ($Pocket[0] as $IX => $Match)
                $Call['Layout'] = str_replace($Match, F::Dot($Call, $Pocket[1][$IX]), $Call['Layout']);

        $Call['Output'] = $Call['Layout'];
        return $Call;
    });