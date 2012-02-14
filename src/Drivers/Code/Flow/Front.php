<?php

    /* Codeine
     * @author BreathLess
     * @description: Фронт контроллер
     * @package Codeine
     * @version 7.1
     * @date 31.08.11
     * @time 1:12
     */

    self::setFn('Run', function ($Call)
    {
        // В этом месте, практически всегда, проихсодит роутинг.

        $Call = F::Run('Code.Flow.Hook', 'Run',  $Call, array('On' => 'beforeRun')); // JP beforeRun

            // Если передан нормальный вызов, совершаем его
            if (F::isCall($Call))
            {
                $Call = F::Run($Call['Service'], $Call['Method'], $Call, $Call['Call']);
            }
            // В противном случае, 404
            else
                $Call = F::Run ('Code.Flow.Hook', 'Run', $Call, array('On' => 'on404'));

        // А здесь - рендеринг
        $Call = F::Run ('Code.Flow.Hook', 'Run', $Call, array('On' => 'afterRun')); // JP afterRun

        return $Call;
    });
