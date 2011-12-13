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
        foreach ($Call['Interfaces'] as $Interface)
            if (F::Run('System.Input.' . $Interface, 'Detect'))
                $Call['Value']  = F::Run('System.Input.'.$Interface, 'Get');

        $Call = F::Run('Code.Flow.Hook', 'Run',  $Call, array('On' => 'beforeRun')); // JP beforeRun

        $Call = F::Run($Call['Value']['Service'], $Call['Value']['Method'], $Call);
        // Передаём его в рендерер

        $Call = F::Run('Engine.View', 'Render', $Call);

        return $Call;
    });
