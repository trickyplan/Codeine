<?php

    /* Codeine
     * @author BreathLess
     * @description: Фронт контроллер
     * @package Codeine
     * @version 7.x
     * @date 31.08.11
     * @time 1:12
     */

    setFn('Run', function ($Call)
    {
        list($Call['Service'], $Call['Method']) = array ($Call['Run']['Service'], $Call['Run']['Method']);

        F::Log('Application *'.$Call['Service'].':'.$Call['Method'].'* started', LOG_IMPORTANT);

        $Call = F::Hook('beforeApplicationRun', $Call); // В этом месте, практически всегда, происходит роутинг.

            $Call = F::Live($Call['Run'], $Call);

        $Call = F::Hook('afterApplicationRun', $Call); // А здесь - рендеринг
        F::Log('Application *'.$Call['Service'].':'.$Call['Method'].'* finished', LOG_IMPORTANT);

        if (is_array($Call))
            $Call['Context'] = '';

        return $Call;
    });
