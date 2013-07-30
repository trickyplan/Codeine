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
        // В этом месте, практически всегда, происходит роутинг.
        $Call = F::Hook('beforeFrontRun', $Call);

        // Если передан нормальный вызов, совершаем его

        if (!isset($Call['Output']))
        {
            if (F::isCall($Call['Run']))
            {
                if (!isset($Call['Failure']))
                {
                    $Slices = explode('.', $Call['Run']['Service']);

                    list($Call['Service'], $Call['Method']) = array($Call['Run']['Service'], $Call['Run']['Method']);
                    $Call['Environment'] = F::Environment();
                    $Call = F::Live($Call['Run'], $Call);
                }
            }
            else
                $Call = F::Hook('NotFound', $Call);

            // В противном случае, 404
            $Call['Context'] = '';
        }

        // А здесь - рендеринг
            $Call = F::Hook('afterFrontRun', $Call);

        return $Call;
    });
