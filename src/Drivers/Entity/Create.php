<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeCreateDo', $Call);

        $Call = F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);

        return $Call;
    });

    self::setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeCreateGet', $Call);

        if (isset($Call['Request']))
            $Call['Data'] = F::Merge($Call['Request'], $Call['Data']);

        $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Main','Context' => $Call['Context']);
        $Call['Layouts'][] = array(
            'Scope' => $Call['Entity'],
            'ID' => isset($Call['Custom Layouts']['Create'])? $Call['Custom Layouts']['Create']: 'Create',
            'Context' => $Call['Context']);

        // Загрузить предопределённые данные и умолчания
        // Сгенерировать форму

        $Call['Output']['Content']['Form'] = [
                'Type' => 'Form',
                'Action' => isset($Call['Action'])?$Call['Action']: ''
        ];

        // Для каждой ноды в модели
        $ic = 0;
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
                    $Widget['Label'] = $Call['Entity'].'.Entity:'.$Name;
                    $Widget['Node'] = $Name;

                    $Widget['Name'] = strtr($Name, '.', '_');

                    $Widget['ID'] = strtr($Name, '.','_');
                    $Widget['Context'] = $Call['Context'];

                    $Widget = F::Merge($Node, $Widget);

                    $Widget['Data'] = $Call['Data'];

                    if($ic == 0)
                        $Widget['Autofocus'] = true;

                    // Если есть значение, добавляем
                    if (isset($Call['Data'][$Name]))
                        $Widget['Value'] = $Call['Data'][$Name];
                    elseif(isset($Node['Default']))
                        $Widget['Value'] = F::Live($Node['Default']);

                    // Помещаем виджет в поток
                    $ic++;

                    $Call = F::Run('Entity.Form.Layout.'.$Call['FormLayout'], 'Add', $Call,
                        array(
                            'IC' => $ic,
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

        if (!isset($Call['Failure']))
        {
            if (isset($Call['Data']))
                $Call['Data'] = F::Merge($Call['Request'], $Call['Data']);
            else
                $Call['Data'] = $Call['Request'];

            foreach ($Call['Nodes'] as $Name => $Node)
            {
                if (!isset($Node['Widgets']) && isset($Call['Data'][$Name]))
                    unset($Call['Data'][$Name]);
            }
            // Отправляем в Entity.Create

            $Call['Data'] = F::Run('Entity', 'Create', $Call);

            // Выводим результат

            $Call = F::Hook('afterCreatePost', $Call);
        }

        return $Call;
    });