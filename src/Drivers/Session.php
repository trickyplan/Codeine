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
                F::Log('Session: Marker added');
        }
        else
        {
            $Call['Session'] = F::Run('Entity', 'Read', ['Entity' => 'Session', 'Where' => $Call['SID'], 'One' => true]);

            if (isset($Call['Session']['Secondary']) && $Call['Session']['Secondary'] != -1)
            {
                $Call['Session']['User'] = F::Run('Entity', 'Read', ['Entity' => 'User', 'Where' => $Call['Session']['Secondary'], 'One' => true]);
                F::Log('Session: Secondary user '.$Call['Session']['Secondary']['ID'].' authenticated');
            }
            elseif (isset($Call['Session']['User']) && $Call['Session']['User'] != -1)
            {
                $Call['Session']['User'] = F::Run('Entity', 'Read', ['Entity' => 'User', 'Where' => $Call['Session']['User'], 'One' => true]);
                F::Log('Session: Primary user '.$Call['Session']['User']['ID'].' authenticated');
            }
        }
        return $Call;
    });

    setFn('Write', function ($Call)
    {
        $Call['Session'] = F::Run('Entity', 'Read', ['Entity' => 'Session', 'Where' => $Call['SID'],'One' => true]);

        if (null === $Call['Session'])
            $Call['Session'] = F::Run('Entity', 'Create', $Call, ['Entity' => 'Session', 'Data' => ['ID' => $Call['SID']], 'One' => true]);
        else
            $Call['Session'] = F::Run('Entity', 'Update', $Call, ['Entity' => 'Session', 'Where' => $Call['SID'], 'One' => true]);

        return $Call;
    });

    setFn('Read', function ($Call)
    {
        $Call = F::Run(null, 'Initialize', $Call);

        if (isset($Call['Key']))
            return F::Dot($Call['Session'], $Call['Key']);
        else
            return $Call['Session'];
    });

    setFn('Annulate', function ($Call)
    {
        if ($Call['Session']['Secondary'] != -1)
            $Call = F::Run('Session', 'Write', $Call, ['Data' => ['Secondary' => -1]]);
        else
            $Call = F::Run('Session', 'Write', $Call, ['Data' => ['User' => -1]]);

        $Call = F::Hook('afterAnnulate', $Call);

        return $Call;
    });