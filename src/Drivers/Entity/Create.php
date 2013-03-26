<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['One']) && isset($Call['Data']))
        {
            $Call['Data'] = [$Call['Data']];
            unset($Call['One']);
        }

        $Call = F::Hook('beforeCreateDo', $Call);

        $Call = F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);

        return $Call;
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeCreateGet', $Call);

        if (isset($Call['Request']['Data']) && isset($Call['Data']))
            $Call['Data'] = F::Merge($Call['Request']['Data'], $Call['Data']);
        else
            if (isset ($Call['Request']['Data']))
                $Call['Data'] = $Call['Request']['Data'];

        $Call['Scope'] = isset($Call['Scope'])? $Call['Scope']: $Call['Scope'] = $Call['Entity'];

        $Call['Layouts'][] = ['Scope' => $Call['Entity'],'ID' => 'Main','Context' => $Call['Context']];

        $Call['Layouts'][] =
            [
                'Scope' => $Call['Scope'],
                'ID' => isset($Call['Custom Layouts']['Create'])?  $Call['Custom Layouts']['Create']: 'Create',
                'Context' => $Call['Context']
            ];

        $Call['Output']['Content']['Form'] = [
                'Type' => 'Form',
                'Action' => isset($Call['Action'])? $Call['Action']: ''
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

                    $Widget['Name']   = 'Data'.'[0]['.strtr($Name, '.               ','_').']';

                    $Widget['ID'] = strtr($Name, '.','_');
                    $Widget['Context'] = $Call['Context'];

                    $Widget = F::Merge($Node, $Widget);

                    if (isset($Call['Data']))
                        $Widget['Data'] = $Call['Data'];

                    if (isset($Widget['Options']))
                        $Widget['Options'] = F::Live($Widget['Options']);
                    else
                        $Widget['Options'] = array();

/*                    if($ic == 0)
                        $Widget['Autofocus'] = true;*/

                    // Если есть значение, добавляем
                    if (isset($Call['Data']))
                        if (($Widget['Value'] =  F::Dot($Call['Data'], $Name)) === null)
                            if (isset($Node['Default']))
                                $Widget['Value'] = F::Live($Node['Default']);

                    // Упростить

                    if (isset($Widget['Value']))
                        $Widget['Value'] = F::Live($Widget['Value']);
                    else
                        $Widget['Value'] = null;

                    // Помещаем виджет в поток
                    $ic++;

                    $Call = F::Run('Entity.Form.Layout.'.$Call['FormLayout'], 'Add', $Call,
                        [
                            'IC' => $ic,
                            'Name' => $Name,
                            'Widget' => $Widget
                        ]);

                    $Call['Widget'] = null;
                }
            }
        }

        // Вывести
        $Call = F::Hook('afterCreateGet', $Call);

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeCreatePost', $Call);
        // Берём данные из запроса


        if (!isset($Call['Failure']))
        {
            /*foreach ($Call['Nodes'] as $Name => $Node)
            {
                if (!isset($Node['Widgets']) && isset($Call['Data'][$Name]))
                    unset($Call['Data'][$Name]);
            }*/ // FIXME Вынести в Strict

            if (isset($Call['Request']['Data']))
            {
                if (isset($Call['Data']))
                    $Call['Data'] = F::Merge($Call['Data'], $Call['Request']['Data']);
                else
                    $Call['Data'] = $Call['Request']['Data'];
            }

            // Отправляем в Entity.Create

            $Call = F::Run('Entity', 'Create', $Call);

            if (!isset($Call['Errors']) or empty($Call['Errors']))
                $Call = F::Hook('afterCreatePost', $Call);
            else
            {
                foreach ($Call['Errors'] as $Name =>$Node)
                    foreach ($Node as $Error)
                        $Call['Output']['Message'][] =
                            [
                                'Type' => 'Block',
                                'Class' => 'alert alert-danger',
                                'Value' => '<l>'.$Call['Entity'].'.Error:'.$Name.'.'.$Error.'</l>'
                            ];

                $Call = F::Run(null, 'GET', $Call);
            }
                // Выводим результат
        }

        return $Call;
    });