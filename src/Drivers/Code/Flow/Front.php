<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Фронт контроллер
     * @package Codeine
     * @version 8.x
     * @date 31.08.11
     * @time 1:12
     */

    setFn('Run', function ($Call)
    {
        // В этом месте, практически всегда, происходит роутинг.
        $Call = F::Hook('beforeFrontRun', $Call);

            // Если передан нормальный вызов, совершаем его
            F::Log('Front Controlled *'.$Call['Service'].':'.$Call['Method'].'* started', LOG_NOTICE);

            if (F::isCall($Call['Run']))
            {
                if (!isset($Call['Run']['Method']))
                    $Call['Run']['Method'] = 'Do';

                list($Call['Service'], $Call['Method'])
                    = [$Call['Run']['Service'], $Call['Run']['Method']];


                if (isset($Call['Run']['Call']))
                    F::Log($Call['Run']['Call'], LOG_INFO);

                    $Call = F::Live($Call['Run'], $Call);
            }

        // А здесь - рендеринг
        $Call = F::Hook('afterFrontRun', $Call);
        return $Call;
    });
