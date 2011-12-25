<?php

    /* Codeine
     * @author BreathLess
     * @description: Фронт контроллер
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 1:12
     */

    self::setFn('Run', function ($Call)
    {
        $Call = F::Run('Code.Flow.Hook', 'Run',  $Call, array('On' => 'beforeRun')); // JP beforeRun

        $Call['Value']['Call'] = isset($Call['Value']['Call']) ? $Call['Value']['Call']: null;

        $Call = F::Run($Call['Value']['Service'], $Call['Value']['Method'], $Call['Value']['Call']);
        // Передаём его в рендерер

        $Call = F::Run('Engine.View', 'Render', $Call);

        return $Call;
    });
