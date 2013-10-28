<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeVerifyDo', $Call);

        if (isset($Call['Where']))
            $Call['Where'] = F::Live($Call['Where']); // FIXME

        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeVerifyGet', $Call);

        if (!isset($Call['Failure']))
        {
            $Call['Output']['Content']['Form Widget'] = ['Type' => 'Form', 'Submit' => 'Verify'];

            $Call['Tag'] = isset($Call['Scope'])? $Call['Scope']: null;

            $Call['Scope'] = isset($Call['Scope'])? $Call['Entity'].'/'.$Call['Scope'] : $Call['Entity'];

            $Call['Layouts'][] =
                [
                    'Scope' => $Call['Entity'],
                    'ID' => isset($Call['Custom Layouts']['Verify'])?
                            $Call['Custom Layouts']['Verify']: 'Verify',
                    'Context' => $Call['Context']
                ];

            // Загрузить предопределённые данные и умолчания

            $Call['Data'] = F::Run('Entity', 'Read', $Call, ['ReRead' => true]);

            if (null === $Call['Data'])
                $Call = F::Hook('NotFound', $Call);
            else
                foreach ($Call['Data'] as $IX => $cData)
                {
                    foreach ($Call['Nodes'] as $Name => $Node)
                    {
                        if (isset($Node['Verifiable']) && $Node['Verifiable'] && isset($cData[$Name]))
                        {
                            $Widget = ['Type'  => 'Form.Verify'];

                            $Widget['Value'] = sha1($cData[$Name]);

                            if (isset($cData['Verified'][$Name]))
                                $Widget['Checked'] = (sha1($cData[$Name]) == $cData['Verified'][$Name]);

                            $Widget['Entity'] = $Call['Entity'];
                            $Widget['Node'] = $Name;
                            $Widget['Name'] = 'Data'.'['.$IX.']';
                            $Widget['Key'] = $Name;
                            $Widget['ID'] = strtr($Name, '.','_');
                            $Widget['Context'] = $Call['Context'];

                            if (isset($Node['Localized']) && $Node['Localized'])
                                $Widget['Label']  = $Call['Entity'].'.Entity:'.$Name.'.Label';
                            else
                                $Widget['Label']  = $Call['Entity'].'.Entity:'.$Name;

                            $Call['Output']['Form'][] = $Widget;
                        }
                    }
                }

            // Вывести

            $Call['Output']['Content']['Form Widget']['Action'] =
                isset($Call['Action'])? $Call['Action']: '';
        }

        $Call = F::Hook('afterVerifyGet', $Call);

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        if (isset($Call['Request']['Data']))
            $Call['Data'] = $Call['Request']['Data'];
        else
            $Call['Data'] = [];

        $Call = F::Hook('beforeVerifyPost', $Call);

        // Отправляем в Entity.Verify

        $Call = F::Apply('Entity', 'Update', $Call);

        $Call['Data'] = F::Merge(F::Run('Entity', 'Read', $Call, ['ReRead' => true]), $Call['Data']);

       // Выводим результат

        if (empty($Call['Errors']))
            $Call = F::Hook('afterVerifyPost', $Call);
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