<?php

    /* Codeine
     * @author BreathLess
     * @description: Routing Engine
     * @package Codeine
     * @version 6.0
     * @date 09.07.11
     * @time 22:00
     */

    self::Fn('beforeRun', function ($Call)
    {
        // А нужен ли роутинг?
        if (F::isCall($Call['Value']))
            return $Call['Value'];

        $NewCall = null;

        $Routers = F::Run(
            array(
                '_N' => 'Code.Strategy.Routing.Static',
                '_F' => 'List',
                'NoBehaviours' => true
            )
        ); // Получаем список роутеров

        foreach ($Routers as $Router)
        {
            // Пробуем роутер из списка...
            $NewCall = F::Run(
                array(
                     '_N' => $Call['_N'].'.'.$Router,
                     '_F' => 'Route',
                     'Value' => $Call['Value'],
                     'NoBehaviours' => true
                )
            );

            // Если результат - валидный вызов, то выходим из перебора
            if (F::isCall($NewCall))
                break;
        }

        // Если ни один роутер не вернул результата
        if ($NewCall === null)
            F::Run($Call, array('N' => 'Code.Flow.Hook','F'=>'Run','On'=>'Routing.Failed'));

        return $NewCall;
    });


    self::Fn('afterRun', function ($Call)
    {
        return $Call;
    });
