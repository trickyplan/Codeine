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
        F::Log('Front Controller started', LOG_IMPORTANT);

        // В этом месте, практически всегда, происходит роутинг.
        $Call = F::Hook('beforeFrontRun', $Call);

        if (!isset($Call['Output']))
        {
            // Если передан нормальный вызов, совершаем его

            if (F::isCall($Call['Run']))
            {
                if (!isset($Call['Failure']))
                {
                    if (!isset($Call['Run']['Method']))
                        $Call['Run']['Method'] = 'Do';

                    list($Call['Service'], $Call['Method'])
                        = [$Call['Run']['Service'], $Call['Run']['Method']];

                    F::Log('*'.$Call['Service'].':'.$Call['Method'].'* started', LOG_IMPORTANT);
                    if (isset($Call['Call']))
                        F::Log($Call['Call'], LOG_INFO);

                    $Call['Environment'] = F::Environment();

                    $Call = F::Live($Call['Run'], $Call);
                }
            }
            else
                $Call = F::Hook('NotFound', $Call);

            // В противном случае, 404
        }

        F::Log('Front Controller finished', LOG_IMPORTANT);

        // А здесь - рендеринг
        $Call = F::Hook('afterFrontRun', $Call);

        return $Call;
    });
