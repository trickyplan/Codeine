<?php

    /* Codeine
     * @author bergstein@trickyplan.com
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

        $Call['Value'] = F::Run('Data.Type.'.$Call['Nodes']['ID']['Type'], 'Write', ['Value' => $Call['Value']]);

        // Подгружаем связанную сущность

        $Element = F::Run('Entity', 'Read', ['One' => true, 'Entity' => $Call['Name'], 'Where' => $Call['Value']]);

        // Если такой сущности нет
        if (empty($Element))
            // Смиряемся
            $Call['Value'] = null;
        else
            // Трогаем связанную сущность
            F::Run('Entity', 'Touch', ['One' => true, 'Entity' => $Call['Name'], 'Where' => $Call['Value']]);

        return $Call['Value'];
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        $Call = F::Apply('Entity', 'Load', $Call, ['Entity' => $Call['Name']]);

        $Call['Value'] = F::Run('Data.Type.'.$Call['Nodes']['ID']['Type'], 'Write', ['Value' => $Call['Value']]);

        return $Call['Value'];
    });

    setFn('Populate', function ($Call)
    {
        return null;
    });