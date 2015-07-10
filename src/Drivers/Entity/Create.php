<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeCreateDo', $Call);

        if (isset($Call['Data']))
            $Call['Data'] = F::Live($Call['Data'], $Call);

        $Call = F::Apply(null, $Call['HTTP']['Method'], $Call);

        return $Call;
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeCreateGet', $Call);

        $Call['Scope'] = isset($Call['Scope'])? $Call['Entity'].'/'.$Call['Scope'] : $Call['Entity'];
        $Call['Tag'] = isset($Call['Scope'])? $Call['Scope']: null;

        $Call['Layouts'][] =
            [
                'Scope' => $Call['Scope'],
                'ID' => isset($Call['Custom Layouts']['Create'])?
                        $Call['Custom Layouts']['Create']: 'Create',
                'Context' => $Call['Context']
            ];

        // Для каждой ноды в модели

        if (isset($Call['Data']))
            ;
        else
            $Call['Data'] = [];

        $Call = F::Apply('Entity.Form', 'Generate', $Call, ['IX' => 0, 'Data!' => $Call['Data']]);

        // Вывести
        $Call = F::Hook('afterCreateGet', $Call);

        $Call['Output']['Content']['Form Widget']['Action'] = isset($Call['Action'])? $Call['Action']: '';

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeCreatePost', $Call);
        // Берём данные из запроса

        if (isset($Call['Request']['Data']))
        {
            if (isset($Call['Data']))
                $Call['Data'] = F::Merge($Call['Request']['Data'], $Call['Data']);
            else
                $Call['Data'] = $Call['Request']['Data'];
        }

        // Отправляем в Entity.Create
        $Result = F::Run('Entity', 'Create', $Call);

        if (!isset($Result['Errors']) or empty($Result['Errors']))
        {
           $Call['Data'] = $Result;
           $Call = F::Hook('afterCreatePost', $Call);
        }
        else
        {
            foreach ($Result['Errors'] as $Name =>$Node)
                foreach ($Node as $Error)
                    $Call['Output']['Message'][] =
                        [
                            'Type' => 'Block',
                            'Class' => 'alert alert-danger',
                            'Value' => '<l>'.$Call['Entity'].'.Error:'.$Name.'.'.$Error.'</l>'
                        ];

            // $Call['Data'] = $Call['Request']['Data'];
            $Call = F::Apply(null, 'GET', $Call, ['Purpose' => 'Correct']);
        }

        // Выводим результат

        return $Call;
    });