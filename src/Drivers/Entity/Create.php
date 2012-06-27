<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeCreateDo', $Call);

        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    self::setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeCreateGet', $Call);

        $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Main');
        $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Create');
        $Call['Locales'][] = $Call['Entity'];

        // Загрузить предопределённые данные и умолчания
        // Сгенерировать форму

        // Для каждой ноды в модели
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            $Widget = null;
            // Если виджеты вообще определены
            if (isset($Node['Widgets']))
            {
                // Определяем, какие именно используем
                if (isset($Node['Widgets'][$Call['Purpose']])) // Для нашего случая
                    $Widget = $Node['Widgets'][$Call['Purpose']];
                elseif (isset($Node['Widgets']['Write'])) // Для записи как таковой
                    $Widget = $Node['Widgets']['Write'];

                if (null !== $Widget)
                {
                    $Widget['Entity'] = $Call['Entity'];
                    $Widget['Node'] = $Name;
                    $Widget['Name'] = strtr($Name, '.','_');
                    $Widget['ID'] = strtr($Name, '.','_');

                    // Если есть значение, добавляем
                    if (isset($Call['Data'][$Name]))
                        $Widget['Value'] = $Call['Data'][$Name];
                    elseif(isset($Node['Default']))
                        $Widget['Value'] = F::Live($Node['Default']);

                    // Помещаем виджет в поток
                    $Call = F::Run('Entity.Form.Layout.'.$Call['FormLayout'], 'Add', $Call,
                        array(
                            'Name' => $Name,
                            'Widget' => $Widget));

                    $Call['Widget'] = null;
                }
            }
        }

        // Вывести


        $Call = F::Hook('afterCreateGet', $Call);

        return $Call;
    });

    self::setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeCreatePost', $Call);

        // Берём данные из запроса

        if (isset($Call['Data']))
            $Call['Data'] = F::Merge($Call['Request'], $Call['Data']);
        else
            $Call['Data'] = $Call['Request'];

        // Отправляем в Entity.Create

        $Call['Data'] = F::Run('Entity', 'Create', $Call);

        // Выводим результат

        $Call = F::Hook('afterCreatePost', $Call);

        return $Call;
    });