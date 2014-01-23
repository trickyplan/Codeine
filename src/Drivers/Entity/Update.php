<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Before', function ($Call)
    {
        $Call['Current'] = F::Run('Entity', 'Read', $Call);

        return $Call;
    });

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeUpdateDo', $Call);
        return F::Run(null, $Call['HTTP']['Method'], $Call);
    });

    setFn('GET', function ($Call)
    {
        // Загрузить предопределённые данные и умолчания

        $Call = F::Hook('beforeUpdateGet', $Call);
        {
            $Call['Output']['Content']['Form Widget'] = ['Type' => 'Form', 'Submit' => 'Update'];

            $Call['Tag'] = isset($Call['Scope'])? $Call['Scope']: null;

            $Call['Scope'] = isset($Call['Scope'])? $Call['Entity'].'/'.$Call['Scope'] : $Call['Entity'];

            $Call['Layouts'][] =
                [
                    'Scope' => $Call['Entity'],
                    'ID' => isset($Call['Custom Layouts']['Update'])?
                            $Call['Custom Layouts']['Update']: 'Update',
                    'Context' => $Call['Context']
                ];

            foreach ($Call['Current'] as $IX => $cData)
                $Call = F::Apply('Entity.Form', 'Generate', $Call, ['IX' => $IX, 'Data!' => $cData]);

            // Вывести

            $Call['Output']['Content']['Form Widget']['Action'] = isset($Call['Action'])? $Call['Action']: '';
        }

        $Call = F::Hook('afterUpdateGet', $Call);

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeUpdatePost', $Call);

            if (isset($Call['Request']['Data']))
                $Call['Data'] = $Call['Request']['Data'];

            // Отправляем в Entity.Update

            $Call['Current'] = F::Run('Entity', 'Update', $Call);
            $Call['Data'] = $Call['Current'];

           // Выводим результат
            if (empty($Call['Errors']))
                $Call = F::Hook('afterUpdatePost', $Call);
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
            }

            $Call = F::Apply(null, 'GET', $Call);

        return $Call;
    });