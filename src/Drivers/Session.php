<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Initialize', function ($Call)
    {
        $Call['SID'] = F::Run('Session.Marker.Cookie', 'Read', $Call);

        // Маркера нет — пользователь чистый гость
        if (null === $Call['SID'])
        {
            F::Log('Session: Marker not set');

            // Генерируем маркер
            $Call['SID'] = F::Live($Call['Generator']['SID']);
            // Вешаем маркер
            if (F::Run('Session.Marker.Cookie', 'Write', $Call))
                F::Log('Session: Marker added', LOG_INFO);
        }
        else
        {
            $Call['Session'] = F::Run('Entity', 'Read',
                [
                    'Entity' => 'Session',
                    'Where' => $Call['SID'],
                    'One' => true
                ]);

            if (isset($Call['Session']['Secondary']) && $Call['Session']['Secondary'] != 0)
            {
                $Call['Session']['Primary'] = F::Run('Entity', 'Read', $Call,
                    [
                        'Entity' => 'User',
                        'Where' => $Call['Session']['User'],
                        'One' => true
                    ]);

                $Call['Session']['User'] = F::Run('Entity', 'Read', $Call,
                    [
                        'Entity' => 'User',
                        'Where' => $Call['Session']['Secondary'],
                        'One' => true
                    ]);

                F::Log('Session: Secondary user '.$Call['Session']['User']['ID'].' authenticated', LOG_INFO);
            }
            elseif (isset($Call['Session']['User']) && $Call['Session']['User'] != 0)
            {
                $Call['Session']['User'] = F::Run('Entity', 'Read',
                    [
                        'Entity' => 'User',
                        'Where' => $Call['Session']['User'],
                        'One' => true
                    ]);

                F::Log('Session: Primary user '.$Call['Session']['User']['ID'].' authenticated', LOG_INFO);
            }
        }

        if (isset($Call['Session']))
            F::Log($Call['Session'], LOG_INFO);

        return $Call;
    });

    setFn('Write', function ($Call)
    {
        $Call['Session'] = F::Run('Entity', 'Read',
            [
                'Entity' => 'Session',
                'Where' => $Call['SID'],
                'One' => true
            ]);

        if (null === $Call['Session'])
            $Call['Session'] = F::Run('Entity', 'Create',
                [
                    'Entity' => 'Session',
                    'Data' =>
                    [
                        'ID' => $Call['SID']
                    ]
                ])['Data'];
        else
            $Call['Session'] = F::Run('Entity', 'Update',
                [
                    'Entity' => 'Session',
                    'Where' => $Call['SID'],
                    'Data' => $Call['Data']
                ])['Data'];

        return $Call;
    });

    setFn('Read', function ($Call)
    {
        if (!isset($Call['Session']))
            $Call = F::Run(null, 'Initialize', $Call);

        if (isset($Call['Key']))
            return F::Dot($Call['Session'], $Call['Key']);
        else
            return $Call['Session'];
    });

    setFn('Annulate', function ($Call)
    {
        if (isset($Call['Session']['Secondary']) && $Call['Session']['Secondary'] != 0)
            $Call = F::Run('Session', 'Write', $Call, ['Data' => ['Secondary' => 0]]);
        else
            $Call = F::Run('Session', 'Write', $Call, ['Data' => ['User' => 0]]);

        $Call = F::Hook('afterAnnulate', $Call);

        return $Call;
    });