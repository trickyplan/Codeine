<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Initialize', function ($Call) {
        $Call['SID'] = F::Run('Session.Marker.Cookie', 'Read', $Call);

        $Call = F::Hook('beforeSessionInitialize', $Call);

        // No Marker — Fresh User
        if (null === $Call['SID']) {
            F::Log('Session *' . $Call['SID'] . '*: Marker does not exist', LOG_INFO, ['Session', 'Security']);

            if (F::Dot($Call, 'Security.Session.Auto')) {
                $Call = F::Run(null, 'Mark', $Call);
            }
        } else {
            $Call['Session'] = F::Run(
                'Entity',
                'Read',
                $Call,
                [
                    'Entity' => 'Session',
                    'Where' => $Call['SID'],
                    'Time' => rand(),
                    'One' => true
                ]
            );

            if ($Call['Session'] !== null) {
                if (isset($Call['Session']['Channel'])) {
                    F::Log(
                        'Session *' . $Call['SID'] . '*: Channel *' . $Call['Session']['Channel'] . '*',
                        LOG_INFO,
                        ['Session', 'Security']
                    );
                }
                /*
                    if (isset($Call['Session']['User']['Locale']))
                    {
                        $Call['Locale'] = $Call['Session']['User']['Locale'];
                        F::Log('User Locale selected: '.$Call['Session']['User']['Locale'], LOG_INFO);
                    }*/
            } else {
                $Call['Session'] = [];
            }
        }

        $Call = F::Hook('afterSessionInitialize', $Call);

        $Call = F::Run(null, 'Load User', $Call);

        $Call['SUID'] = isset($Call['Session']['User']['ID']) ? 'U:' . $Call['Session']['User']['ID'] : 'S:' . $Call['SID'];

        if (isset($Call['Session'])) {
        }// F::Log(function() use ($Call) {return $Call['Session'];}, LOG_INFO, 'Security');
        else {
            $Call['Session'] = [];
        }

        return $Call;
    });

    setFn('Load User', function ($Call) {
        if (isset($Call['Session']['Secondary']) && !empty($Call['Session']['Secondary'])) {
            $Call['Session']['Primary'] = F::Run(
                'Entity',
                'Read',
                $Call,
                [
                    'Entity' => 'User',
                    'Where' => $Call['Session']['User'],
                    'Time' => microtime(true),
                    'One' => true
                ]
            );

            $Call['Session']['User'] = F::Run(
                'Entity',
                'Read',
                $Call,
                [
                    'Entity' => 'User',
                    'Where' => $Call['Session']['Secondary'],
                    'Time' => microtime(true),
                    'One' => true
                ]
            );

            F::Log(
                'Session *' . $Call['SID'] . '*: Secondary user *' . $Call['Session']['User']['ID'] . '* authenticated, primary user is *' . $Call['Session']['Primary']['ID'] . '*',
                LOG_INFO,
                ['Session', 'Security']
            );

            $Call = F::Hook('LoadUser.After', $Call);
        } elseif (isset($Call['Session']['User']) && !empty($Call['Session']['User'])) {
            $Call['Session']['User'] = F::Run(
                'Entity',
                'Read',
                [
                    'Entity' => 'User',
                    'Where' => isset($Call['Session']['User']['ID']) ? $Call['Session']['User']['ID'] : $Call['Session']['User'],
                    'Time' => microtime(true),
                    'One' => true
                ]
            );

            if ($Call['Session']['User'] == null) {
            } //F::Run(null, 'Annulate', $Call);
            else {
                F::Log(
                    'Session *' . $Call['SID'] . '*: Primary user ' . $Call['Session']['User']['ID'] . ' authenticated',
                    LOG_INFO,
                    ['Session', 'Security']
                );
                $Call = F::Hook('LoadUser.After', $Call);
            }
        }

        if (isset($Call['Session']['User']['Status']) && $Call['Session']['User']['Status'] === 0) {
            $Call = F::Hook('ActivationNeeded', $Call);
        }

        return $Call;
    });

    setFn('Write', function ($Call) {
        if (isset($Call['SID'])) {
        } else {
            $Call = F::Apply(null, 'Mark', $Call);
        }

        if (isset($Call['Session'])) {
        } else {
            $Call = F::Apply(null, 'Initialize', $Call);
        }

        if (empty($Call['Session'])) {
            $Call['Session Data']['ID'] = $Call['SID'];
            $Call['Session'] = F::Run(
                'Entity',
                'Create',
                $Call,
                [
                    'Entity' => 'Session',
                    'One' => true,
                    'Data' => $Call['Session Data']
                ]
            );
            F::Log('Session *' . $Call['SID'] . '*:  created ', LOG_INFO, ['Session', 'Security']);
            F::Log(
                'Session *' . $Call['SID'] . '*:  data: ' . j($Call['Session Data']),
                LOG_INFO,
                ['Session', 'Security']
            );
        } else {
            $Call['Session Data'] = F::Merge($Call['Session'], $Call['Session Data']);
            $Call['Session Data']['ID'] = $Call['SID'];

            if (isset($Call['Session Data']['User']['ID'])) { // FIXME ASAP
                $Call['Session Data']['User'] = $Call['Session Data']['User']['ID'];
            }


            $Call['Session'] = F::Run(
                'Entity',
                'Update',
                [
                    'Entity' => 'Session',
                    'Data' => $Call['Session Data'],
                    'Where' => $Call['SID'],
                    'Time' => microtime(true),
                    'One' => true
                ]
            );

            F::Log(
                'Session *' . $Call['SID'] . '* updated (' . j($Call['Session Data']) . ')',
                LOG_INFO,
                ['Session', 'Security']
            );
        }

        $Call = F::Run(null, 'Load User', $Call);

        return $Call;
    });

    setFn('Read', function ($Call) {
        if (!isset($Call['Session'])) {
            $Call = F::Apply(null, 'Initialize', $Call);
        }

        if (isset($Call['Key'])) {
            return F::Dot($Call['Session'], $Call['Key']) or false;
        } else {
            return $Call['Session'];
        }
    });

    setFn('Annulate', function ($Call) {
        $Call = F::Hook('beforeAnnulate', $Call);

        if (F::Dot($Call, 'Session.Secondary')) {
            $PrimaryID = $Call['Session']['Primary']['ID'];
            $SecondaryID = $Call['Session']['Secondary'];
            $Call = F::Apply(
                'Session',
                'Write',
                $Call,
                ['Session Data' => ['User' => $Call['Session']['Primary']['ID'], 'Secondary' => null]]
            );
            F::Log(
                'Session *' . $Call['SID'] . '*: Detached secondary user: ' . $SecondaryID . ', primary user is *' . $PrimaryID . '*',
                LOG_NOTICE,
                ['Session', 'Security']
            );
        } else {
            $PrimaryID = $Call['Session']['User']['ID'];
            $Call = F::Apply('Session', 'Write', $Call, ['Session Data' => ['User' => null]]);
            F::Log(
                'Session *' . $Call['SID'] . '*: Detached primary user: ' . $PrimaryID,
                LOG_NOTICE,
                ['Session', 'Security']
            );
        }

        $Call = F::Hook('afterAnnulate', $Call);

        $Call['Session'] = [];

        return $Call;
    });

    setFn('Mark', function ($Call) {
        if (isset($Call['SID'])) {
        } else {
            $Call['SID'] = F::Live(F::Dot($Call, 'Security.Session.Generator'));
        }

        // Вешаем маркер, если включено автомаркирование
        $Call = F::Apply('Session.Marker.Cookie', 'Write', $Call);

        if (isset($Call['Session']['Marker']) && $Call['Session']['Marker']) {
            F::Log('Session *' . $Call['SID'] . '*: Marker created', LOG_INFO, ['Session', 'Security']);
            unset($Call['Session']['Marker']);
        } else {
            F::Log('Session *' . $Call['SID'] . '*: Marker failed to create', LOG_WARNING, ['Session', 'Security']);
        }

        return $Call;
    });
