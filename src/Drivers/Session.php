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
            F::Log('Session: Marker does not exist', LOG_INFO, 'Security');

            if (isset($Call['Session Auto']) && $Call['Session Auto'])
                $Call = F::Run(null, 'Mark', $Call);
        }
        else
        {
            $Call['Session'] = F::Run('Entity', 'Read', $Call,
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

        $Call['SUID'] = isset($Call['Session']['User']['ID'])? 'U:'.$Call['Session']['User']['ID']: 'S:'. $Call['SID'];

        if (isset($Call['Session']))
            F::Log(function() use ($Call) {return $Call['Session'];}, LOG_INFO, 'Security');
        else
            $Call['Session'] = [];

        return $Call;
    });

    setFn('Load User', function ($Call)
    {
        if (isset($Call['Session']['Secondary']) && !empty($Call['Session']['Secondary']))
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
        elseif (isset($Call['Session']['User']) && !empty($Call['Session']['User']))
        {
            $Call['Session']['User'] = F::Run('Entity', 'Read', $Call,
                [
                    'Entity' => 'User',
                    'Where' => $Call['Session']['User'],
                    'Time' => microtime(true),
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
            $Call['Session'] = F::Run('Entity', 'Create', $Call,
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
            
            $Call['Session'] = F::Run('Entity', 'Update', $Call,
                [
                    'Entity' => 'Session',
                    'Data' => $Call['Session Data'],
                    'Where' => $Call['SID'],
                    'Time' => microtime(true),
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
        $Call = F::Hook('beforeAnnulate', $Call);
        
        if (isset($Call['Session']['Secondary']) && $Call['Session']['Secondary'] != 0)
        {
            $Call = F::Apply('Session', 'Write', $Call, ['Session Data' => ['Secondary' => null]]);
            F::Log('Detached secondary user: '.$Call['Session']['Secondary'], LOG_INFO, 'Security');
        }
        else
        {
            $Call = F::Apply('Session', 'Write', $Call, ['Session Data' => ['User' => null]]);
            // F::Log('Detached primary user: '.$Call['Session']['User']['ID'], LOG_INFO, 'Security');
        }

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
        $Call = F::Apply('Session.Marker.Cookie', 'Write', $Call);
        
        if (isset($Call['Session']['Marker']) && $Call['Session']['Marker'])
            F::Log('Session: Marker created', LOG_INFO, 'Security');
        else
            F::Log('Session: Marker failed to create', LOG_WARNING, 'Security');

        return $Call;
    });