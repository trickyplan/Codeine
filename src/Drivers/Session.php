<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Initialize', function ($Call)
    {
        $Call['SID'] = F::Run('Session.Marker.Cookie', 'Read', $Call);

        $Call = F::Hook('beforeSessionInitialize', $Call);

        // Маркера нет — пользователь чистый гость
        if (null === $Call['SID'])
        {
            F::Log('Session: Marker not set', LOG_DEBUG, 'Security');
            $Call['Session'] = [];
        }
        else
        {
            $Call['Session'] = F::Run('Entity', 'Read',
                [
                    'Entity' => 'Session',
                    'Where' => $Call['SID'],
                    'Time' => rand(),
                    'One' => true
                ]);

                if ($Call['Session'] !== null)
                {
                    if (isset($Call['Session']['Channel']))
                        F::Log('Session: Channel *'.$Call['Session']['Channel'].'*', LOG_INFO, 'Security');
                /*
                    if (isset($Call['Session']['User']['Locale']))
                    {
                        $Call['Locale'] = $Call['Session']['User']['Locale'];
                        F::Log('User Locale selected: '.$Call['Session']['User']['Locale'], LOG_INFO);
                    }*/
                }
                else
                    $Call['Session'] = [];
        }

        $Call = F::Hook('afterSessionInitialize', $Call);

        $Call = F::Run(null, 'Load User', $Call);

        F::Log($Call['Session'], LOG_DEBUG, 'Security');

        return $Call;
    });

    setFn('Load User', function ($Call)
    {
        if (isset($Call['Session']['Secondary']) && $Call['Session']['Secondary'] != 0)
        {
            $Call['Session']['Primary'] = F::Run('Entity', 'Read', $Call,
                [
                    'Entity' => 'User',
                    'Where' => $Call['Session']['User'],
                    'Time' => microtime(true),
                    'One' => true
                ]);

            $Call['Session']['User'] = F::Run('Entity', 'Read', $Call,
                [
                    'Entity' => 'User',
                    'Where' => $Call['Session']['Secondary'],
                    'Time' => microtime(true),
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

        if (isset($Call['Session']['User']['Status']) && $Call['Session']['User']['Status'] === 0)
            $Call = F::Hook('ActivationNeeded', $Call);

        return $Call;
    });

    setFn('Write', function ($Call)
    {
        if (isset($Call['SID']))
            ;
        else
            $Call = F::Apply(null, 'Mark', $Call);

        if (isset($Call['Session']))
            ;
        else
            $Call = F::Apply(null, 'Initialize', $Call);

        if (empty($Call['Session']))
        {
            $Call['Session Data']['ID'] = $Call['SID'];
            $Call['Session'] = F::Run('Entity', 'Create',
                [
                    'Entity' => 'Session',
                    'One' => true,
                    'Data' => $Call['Session Data']
                ]);

            F::Log('Session created '.$Call['SID'], LOG_INFO, 'Security');
            F::Log('Session data '.j($Call['Session Data']), LOG_INFO, 'Security');
        }
        else
        {
            $Call['Session Data']['ID'] = $Call['SID'];
            $Call['Session'] = F::Run('Entity', 'Update',
                [
                    'Entity' => 'Session',
                    'Data' => $Call['Session Data'],
                    'Where' => $Call['SID'],
                    'One' => true
                ]);

            F::Log('Session updated '.$Call['SID'], LOG_INFO, 'Security');
        }

        $Call = F::Run(null, 'Load User', $Call);

        return $Call;
    });

    setFn('Read', function ($Call)
    {
        if (!isset($Call['Session']))
            $Call = F::Apply(null, 'Initialize', $Call);

        if (isset($Call['Key']))
            return F::Dot($Call['Session'], $Call['Key']) or false;
        else
            return $Call['Session'];
    });

    setFn('Annulate', function ($Call)
    {
        if (isset($Call['Session']['Secondary']) && $Call['Session']['Secondary'] != 0)
            $Call = F::Apply('Session', 'Write', $Call, ['Session Data' => ['Secondary' => 0]]);
        else
            $Call = F::Apply('Session', 'Write', $Call, ['Session Data' => ['User' => 0]]);

        $Call = F::Hook('afterAnnulate', $Call);

        $Call['Session'] = [];

        return $Call;
    });

    setFn('Mark', function ($Call)
    {
        if (isset($Call['SID']))
            ;
        else
            $Call['SID'] = F::Live($Call['SID Generator']);

        // Вешаем маркер, если включено автомаркирование
        if (F::Run('Session.Marker.Cookie', 'Write', $Call))
            F::Log('Session Marker added', LOG_INFO, 'Security');
        else
            F::Log('Session Marker add failed', LOG_INFO, 'Security');

        return $Call;
    });