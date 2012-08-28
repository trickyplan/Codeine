<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    self::setFn('Do', function ($Call)
    {
        $Call['Purpose'] = 'Update';
        $Call['Where'] = F::Live($Call['Where']); // FIXME

        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    self::setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeUpdateGet', $Call);

        $Call['Locales'][] = $Call['Entity'];

        $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Main','Context' => $Call['Context']);
        $Call['Layouts'][] = array(
            'Scope' => $Call['Entity'],
            'ID' => isset($Call['Custom Layouts']['Create'])? $Call['Custom Layouts']['Update']: 'Update',
            'Context' => $Call['Context']);

        // Загрузить предопределённые данные и умолчания

        $Call['Data'] = F::Run('Entity', 'Read', $Call)[0];

        // Сгенерировать форму

        // Для каждой ноды в модели
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            // Если виджеты вообще определены
            if (isset($Node['Widgets']) && !isset($Node['WriteOnce']))
            {
                $Widget = null;
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

                    $Widget = F::Merge($Node, $Widget);

                    // Если есть значение, добавляем
                    $Widget['Value'] = F::Dot($Call['Data'], $Name);

                    // Помещаем виджет в поток
                    $Call = F::Run('Entity.Form.Layout.'.$Call['FormLayout'], 'Add', $Call,
                        array(
                            'Name' => $Name,
                            'Node' => $Node,
                            'Widget' => F::Merge($Node, $Widget)));

                    $Call['Widget'] = null;
                }
            }
        }

        // Вывести

        $Call = F::Hook('afterUpdateGet', $Call);

        return $Call;
    });

    self::setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeUpdatePost', $Call);

        // Берём данные из запроса

        $Call['Data'] = $Call['Request'];

        // Отправляем в Entity.Update

        $Call['Data'] = F::Run('Entity', 'Update', $Call);

        $Call['Data'] = F::Run('Entity', 'Read', $Call)[0];

       // Выводим результат

        $Call = F::Hook('afterUpdatePost', $Call);

        return $Call;
    });