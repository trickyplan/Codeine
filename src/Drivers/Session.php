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
        $Call['Session'] = null;

        // Маркера нет — пользователь чистый гость
        if (null === $Call['SID'])
        {
            F::Log('Session: Marker not set', LOG_DEBUG, 'Security');

            if (isset($Call['Session Auto']) && $Call['Session Auto'])
                $Call = F::Apply(null, 'Mark', $Call);

        }
        else
        {
            $Call['Session'] = F::Run('Entity', 'Read',
                [
                    'Entity' => 'Session',
                    'Where' => $Call['SID'],
                    'ReRead' => true,
                    'One' => true
                ]);

            if ($Call['Session'] !== null)
            {
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

                    F::Log('Session: Secondary user '.$Call['Session']['User']['ID'].' authenticated', LOG_INFO, 'Security');
                }
                elseif (isset($Call['Session']['User']) && $Call['Session']['User'] != 0)
                {
                    $Call['Session']['User'] = F::Run('Entity', 'Read',
                        [
                            'Entity' => 'User',
                            'Where' => $Call['Session']['User'],
                            'One' => true
                        ]);

                    F::Log('Session: Primary user '.$Call['Session']['User']['ID'].' authenticated', LOG_INFO, 'Security');
                }

                if (isset($Call['Session']['User']['Locale']))
                {
                    $Call['Locale'] = $Call['Session']['User']['Locale'];
                    F::Log('User Locale selected: '.$Call['Session']['User']['Locale'], LOG_INFO);
                }
            }
        }

        F::Log($Call['Session'], LOG_DEBUG);

        return $Call;
    });

    setFn('Write', function ($Call)
    {
        if (!isset($Call['SID']))
            $Call = F::Apply(null, 'Mark', $Call);

        if (!isset($Call['Session']))
            $Call = F::Apply(null, 'Initialize', $Call);

        if (null === $Call['Session'])
        {
            $Call['Data']['ID'] = $Call['SID'];
            $Call['Session'] = F::Run('Entity', 'Create', $Call, ['Entity' => 'Session', 'One' => true]);

            F::Log('Session created '.$Call['SID'], LOG_INFO, 'Security');
        }
        else
        {
            $Call['Session'] = F::Run('Entity', 'Update', $Call,
                [
                    'Entity' => 'Session',
                    'Data' => $Call['Data'],
                    'Where' => $Call['SID'],
                    'One' => true
                ]);

            F::Log('Session updated '.$Call['SID'], LOG_INFO, 'Security');
        }
        return $Call;
    });

    setFn('Read', function ($Call)
    {
        if (!isset($Call['Session']))
            $Call = F::Apply(null, 'Initialize', $Call);

        if (isset($Call['Session']))
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
        $Call['SID'] = F::Live($Call['SID Generator']);

            // Вешаем маркер, если включено автомаркирование
            if (F::Run('Session.Marker.Cookie', 'Write', $Call))
                F::Log('Session: Marker added', LOG_INFO);

        return $Call;
    });