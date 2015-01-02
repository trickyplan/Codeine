<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        // Грузим модель связанной сущности
        $Call = F::Apply('Entity', 'Load', $Call, ['Entity' => $Call['Name']]);

        // Приводим значение к типу данных ID связанной сущности
        // Если ID цифровое, то и наш ключ должен быть цифровой

        foreach ($Call['Value'] as &$Value)
        {
            $Value = F::Run('Data.Type.'.$Call['Nodes']['ID']['Type'], 'Write', ['Value' => $Value]);

            // Подгружаем связанную сущность

            $Element = F::Run('Entity', 'Read', ['One' => true, 'Entity' => $Call['Name'], 'Where' => $Value]);

            // Если такой сущности нет
            if (empty($Element))
                // Смиряемся
                $Value = null;
            else
                // Трогаем связанную сущность
                F::Run('Entity', 'Touch', ['One' => true, 'Entity' => $Call['Name'], 'Where' => $Call['Value']]);
        }



        return $Call['Value'];
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        $Call = F::Apply('Entity', 'Load', $Call, ['Entity' => $Call['Name']]);

        foreach ($Call['Value'] as &$Value)
            $Value = F::Run('Data.Type.'.$Call['Nodes']['ID']['Type'], 'Write', ['Value' => $Value]);

        return $Call['Value'];
    });

    setFn('Populate', function ($Call)
    {
        return null;
    });