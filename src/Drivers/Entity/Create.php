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
        $Call = F::Hook('before'.F::Dot($Call, 'Entity').'CreateDo', $Call);

        if (isset($Call['Data']))
            $Call['Data'] = F::Live($Call['Data'], $Call);
        
        if (F::Dot($Call, 'Create.Blind'))
            $Call = F::Apply(null, 'POST', $Call);
        else
            $Call = F::Apply(null, $Call['HTTP']['Method'], $Call);
        
        return $Call;
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeCreateGet', $Call);
        $Call = F::Hook('before'.F::Dot($Call, 'Entity').'CreateGet', $Call);

        $Call['Scope'] = isset($Call['Scope'])? $Call['Entity'].'/'.$Call['Scope'] : $Call['Entity'];
        $Call['Tag'] = isset($Call['Scope'])? $Call['Scope']: null;

        $Call['Layouts'][] =
            [
                'Scope' => $Call['Scope'],
                'ID' => isset($Call['Custom Layouts']['Create'])?
                        $Call['Custom Layouts']['Create']: 'Create',
                'Context' => $Call['Context']
            ];
        
        $Call['Output']['Content']['Form Widget'] = ['Type' => 'Form/'.$Call['Form']['Template'], 'ID' => 'Create'];

        if (isset($Call['Data']))
            ;
        else
            $Call['Data'] = [];

        if (isset($Call['Request']['Data']))
        {
            // Для каждой ноды в модели
            foreach ($Call['Nodes'] as $Node => $NodeOptions)
                if (isset($NodeOptions['Widgets']['Write']) or isset($NodeOptions['Create']))
                {
                    if (F::Dot($Call['Request']['Data'], $Node) === null or F::Dot($Call['Data'], $Node) !== null) // Data > Request
                        ;
                    else
                        $Call['Data'] = F::Dot($Call['Data'], $Node, F::Dot($Call['Request']['Data'], $Node));
                }
        }

        if (empty($Call['Action']))
            $Call['Action'] =$Call['HTTP']['URI'];
        
        $Call['Output']['Content']['Form Widget']['Action'] = $Call['Action'];
        
        $Call = F::Apply('Entity.Form', 'Generate', $Call, ['IX' => 0, 'Data!' => $Call['Data']]);

        // Вывести
        $Call = F::Hook('afterCreateGet', $Call);
        $Call = F::Hook('after'.F::Dot($Call, 'Entity').'CreateGet', $Call);
        
        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeCreatePost', $Call);
        $Call = F::Hook('before'.F::Dot($Call, 'Entity').'CreatePost', $Call);
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
            $Call = F::Hook('after'.F::Dot($Call, 'Entity').'CreatePost', $Call);
            $Call['Output']['Message'][] =
                [
                    'Type' => 'Block',
                    'Class' => 'alert alert-success',
                    'Value' => '<l>'.$Call['Entity'].'.Create:Success</l>'
                ];
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