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

            if (isset($Call['Session Auto']) && $Call['Session Auto'])
                $Call = F::Apply(null, 'Mark', $Call);
        }
        else
        {
            $Call['Session'] = F::Run('Entity', 'Read',
                [
                    'Entity' => 'Session',
                    'Where' => $Call['SID'],
                    'One' => true,
                    'ReRead' => true
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

            if (isset($Call['Session']['User']['Locale']))
            {
                $Call['Locale'] = $Call['Session']['User']['Locale'];
                F::Log('User Locale selected: '.$Call['Session']['User']['Locale'], LOG_INFO);
            }

        }

        if (isset($Call['Session']))
            F::Log($Call['Session'], LOG_DEBUG);

        return $Call;
    });

    setFn('Write', function ($Call)
    {
        if (!isset($Call['SID']))
            $Call = F::Apply(null, 'Mark', $Call);

        $Call['Session'] = F::Run('Entity', 'Read',
            [
                'Entity' => 'Session',
                'Where' => $Call['SID'],
                'One' => true
            ]);

        $Call['Data']['ID'] = $Call['SID'];

        if (null === $Call['Session'])
        {
            $Call['Session'] = F::Run('Entity', 'Create', $Call,
                 [
                     'Entity' => 'Session',
                     'Data' => $Call['Data'],
                     'One' => true
                 ])['Data'];

            F::Log('Session created '.$Call['SID'], LOG_INFO);
        }
        else
        {
            $Call['Session'] = F::Run('Entity', 'Update',
                [
                    'Entity' => 'Session',
                    'Where' => $Call['SID'],
                    'One' => true,
                    'Data' => $Call['Data']
                ])['Data'];

            F::Log('Session updated '.$Call['SID'], LOG_INFO);
        }

        $Call = F::Apply(null, 'Initialize', $Call);

        return $Call;
    });

    setFn('Read', function ($Call)
    {
        if (!isset($Call['Session']))
            $Call = F::Apply(null, 'Initialize', $Call);

        if (isset(isset($Call['Session']))
        {
            if (isset($Call['Key']))
                return F::Dot($Call['Session'], $Call['Key']);
            else
                return $Call['Session'];
        }
    });

    setFn('Annulate', function ($Call)
    {
        if (isset($Call['Session']['Secondary']) && $Call['Session']['Secondary'] != 0)
            $Call = F::Apply('Session', 'Write', $Call, ['Data' => ['Secondary' => 0]]);
        else
            $Call = F::Apply('Session', 'Write', $Call, ['Data' => ['User' => 0]]);

        $Call = F::Hook('afterAnnulate', $Call);

        return $Call;
    });

    setFn('Mark', function ($Call)
    {
        $Call['SID'] = F::Live($Call['Generator']['SID']);

            // Вешаем маркер, если включено автомаркирование
            if (F::Run('Session.Marker.Cookie', 'Write', $Call))
                F::Log('Session: Marker added', LOG_INFO);

        return $Call;
    });