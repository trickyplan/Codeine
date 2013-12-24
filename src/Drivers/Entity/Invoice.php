<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeEntityInvoice', $Call);
            $Call = F::Run(null, $Call['HTTP']['Method'], $Call);
        $Call = F::Hook('afterEntityInvoice', $Call);

        return $Call;
    });

    setFn('GET', function ($Call)
    {
        // Превью счёта
        $Invoice =
        [

        ];
        // Форма
        return $Call;
    });

    setFn('POST', function ($Call)
    {
        // Генерируем счёт
        // Отправляем на список платёжных систем

        return $Call;
    });