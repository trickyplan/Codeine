<?php

    /* Codeine
     * @author BreathLess
     * @description: Цепочный вызыватель
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 1:58
     */

    self::Fn('Run', function ($Call)
    {
        $Steps = $Call['Steps'];

        // TODO Не забыть в контракте указать, что аргументов минимум 2.
        // Выполняем первый вызов отдельно, он будет базой для цикла.
        $Base = F::Run($Steps[0]);
        unset ($Steps[0]);

        foreach ($Steps as $Step)
        {
            // Здесь мы выполняем шаги по очереди, передавая каждому в качестве аргумента возвращаемое значение предыдущего шага
            $Base = F::Run(
                F::Merge($Step,
                    array(
                        'Value' => $Base // Value - название аргумента по умолчанию, для всех функций
                    )));
        }

        return $Base;
    });
