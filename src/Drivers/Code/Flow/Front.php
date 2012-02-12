<?php

    /* Codeine
     * @author BreathLess
     * @description: Фронт контроллер
     * @package Codeine
     * @version 7.0
     * @date 31.08.11
     * @time 1:12
     */

    self::setFn('Run', function ($Call)
    {
        $Call = F::Run('Code.Flow.Hook', 'Run',  $Call, array('On' => 'beforeRun')); // JP beforeRun


        $Call['Value']['Call'] = isset($Call['Value']['Call']) ? $Call['Value']['Call']: null;

        if (isset($Call['Value']['Service']) && isset($Call['Value']['Method']))
        {
            $Call['Front'] = array('Service' => $Call['Value']['Service'], 'Method' => $Call['Value']['Method']);
             // FIXME, I'm shitcode

            $Call = F::Run($Call['Value']['Service'], $Call['Value']['Method'], $Call, $Call['Value']['Call']);
        }
        else
            $Call = F::Run ('Code.Flow.Hook', 'Run', $Call, array('On' => 'on404'));

        $Call = F::Run ('Code.Flow.Hook', 'Run', $Call, array('On' => 'afterRun')); // JP afterRun

        // Передаём его в рендерер

        $Call = F::Run('Engine.View', 'Render', $Call);

        return $Call;
    });
