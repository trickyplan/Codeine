<?php

    /* Codeine
     * @author BreathLess
     * @description: Фронт контроллер
     * @package Codeine
     * @version 7.4.5
     * @date 31.08.11
     * @time 1:12
     */

    self::setFn('Run', function ($Call)
    {
        // В этом месте, практически всегда, происходит роутинг.

        $Call = F::Hook('beforeRun', $Call);

        // Если передан нормальный вызов, совершаем его

        if (!isset($Call['Output']))
        {
            if (F::isCall($Call['Run']))
            {
                $Slices = explode('.', $Call['Run']['Service']);
                $Call['Locales'][] = $Slices[0];
                $Call['Locales'][] = $Slices[0].':'.implode('.', array_slice($Slices, 1));

                list($Call['Service'], $Call['Method']) = array($Call['Run']['Service'], $Call['Run']['Method']);
                $Call = F::Live($Call['Run'], $Call);
            }
            // В противном случае, 404
            else
                $Call = F::Hook('on404', $Call);
        }

        $Call['Context'] = '';
        // А здесь - рендеринг
        $Call = F::Hook('afterRun', $Call);


        return $Call;
    });
