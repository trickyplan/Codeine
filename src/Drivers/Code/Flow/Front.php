<?php

    /* Codeine
     * @author BreathLess
     * @description: Фронт контроллер
     * @package Codeine
     * @version 7.2
     * @date 31.08.11
     * @time 1:12
     */

    self::setFn('Run', function ($Call)
    {
        // В этом месте, практически всегда, происходит роутинг.

        $Call = F::Run('Code.Flow.Hook', 'Run',  $Call, array('On' => 'beforeRun')); // JP beforeRun
            // Если передан нормальный вызов, совершаем его
            if (F::isCall($Call['Run']))
            {
                list($Call['Service'], $Call['Method']) = array($Call['Run']['Service'], $Call['Run']['Method']);
                $Call = F::Live($Call['Run'], $Call);

                $Slices = explode('.', $Call['Run']['Service']);

                $Call['Locales'][] = $Slices[0];
                $Call['Locales'][] = $Slices[0].':'.implode('.', array_slice($Slices, 1));
            }
            // В противном случае, 404
            else
                $Call = F::Hook('on404', $Call);

        // А здесь - рендеринг
        $Call = F::Run ('Code.Flow.Hook', 'Run', $Call, array('On' => 'afterRun')); // JP afterRun

        return $Call;
    });
