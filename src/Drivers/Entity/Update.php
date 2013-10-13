<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeUpdateDo', $Call);

        if (isset($Call['Where']))
            $Call['Where'] = F::Live($Call['Where']); // FIXME

        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeUpdateGet', $Call);

        if (!isset($Call['Failure']))
        {
            $Call['Output']['Content']['Form Widget'] = ['Type' => 'Form', 'Submit' => 'Update'];

            $Call['Tag'] = isset($Call['Scope'])? $Call['Scope']: null;

            $Call['Scope'] = isset($Call['Scope'])? $Call['Entity'].'/'.$Call['Scope'] : $Call['Entity'];

            $Call['Layouts'][] =
                [
                    'Scope' => $Call['Entity'],
                    'ID' => isset($Call['Custom Layouts']['Update'])? $Call['Custom Layouts']['Update']: 'Update',
                    'Context' => $Call['Context']
                ];

            // Загрузить предопределённые данные и умолчания

            $Call['Data'] = F::Run('Entity', 'Read', $Call, ['ReRead' => true]);

            if (null === $Call['Data'])
                $Call = F::Hook('NotFound', $Call);
            else
                foreach ($Call['Data'] as $IX => $cData)
                    $Call = F::Apply('Entity.Form', 'Generate', $Call, ['IX' => $IX, 'Data!' => $cData]);

            // Вывести

            $Call['Output']['Content']['Form Widget']['Action'] = isset($Call['Action'])? $Call['Action']: '';
        }

        $Call = F::Hook('afterUpdateGet', $Call);

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        if (isset($Call['Request']['Data']))
        {
            if (isset($Call['Data']))
                $Call['Data'] = F::Merge($Call['Data'], $Call['Request']['Data']);
            else
                $Call['Data'] = $Call['Request']['Data'];
        }

        $Call = F::Hook('beforeUpdatePost', $Call);

        // Отправляем в Entity.Update

        $Call = F::Apply('Entity', 'Update', $Call);

        $Call['Data'] = F::Merge(F::Run('Entity', 'Read', $Call, ['ReRead' => true]), $Call['Data']);

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

            $Call = F::Apply(null, 'GET', $Call);
        }

        return $Call;
    });