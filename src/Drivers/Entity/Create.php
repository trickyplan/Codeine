<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeCreateDo', $Call);

        $Call = F::Apply(null, $Call['HTTP Method'], $Call);

        return $Call;
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeCreateGet', $Call);

        if (isset($Call['Request']['Data'][0]) && isset($Call['Data']))
            $Call['Data'] = F::Merge($Call['Request']['Data'][0], $Call['Data']);
        else
            if (isset ($Call['Request']['Data'][0]))
                $Call['Data'] = $Call['Request']['Data'][0];

        $Call['Tag'] = isset($Call['Scope'])? $Call['Scope']: null;

        $Call['Scope'] = isset($Call['Scope'])? $Call['Entity'].'/'.$Call['Scope'] : $Call['Entity'];

        $Call['Layouts'][] =
            [
                'Scope' => $Call['Scope'],
                'ID' => isset($Call['Custom Layouts']['Create'])?
                        $Call['Custom Layouts']['Create']: 'Create',
                'Context' => $Call['Context']
            ];

        // Для каждой ноды в модели

        if (!isset($Call['Data'][0]))
            $Call['Data'] = [[]];

        $Call = F::Apply('Entity.Form', 'Generate', $Call, ['IX' => 0, 'Data!' => $Call['Data'][0]]);

        // Вывести
        $Call = F::Hook('afterCreateGet', $Call);

        $Call['Output']['Content']['Form Widget']['Action'] = isset($Call['Action'])? $Call['Action']: '';

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
            $Call = F::Apply('Entity', 'Create', $Call);

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

                $Call = F::Apply(null, 'GET', $Call);
            }
                // Выводим результат
        }

        return $Call;
    });