<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: D8 Port
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 16.11.10
     * @time 3:48
     */

    $Render = function ($Call)
    {
        // Определить тип данных

        // Контроллер отдаёт набор UI компонентов

        $Output = '';

        if (is_array($Call['Body']['Items']))
            foreach ($Call['Body']['Items'] as $Item)
                $Output.= Code::Run(
                    array('F'=>'View/UI/Process',
                          'D'=>$Item ['UI'],
                          'Item'=>$Item)
                );

        // Вытащить шаблоны



        // Профьюзить
        // Постпроцессинг
        // Вернуть

        return $Output;
    };
