<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call) {
        $Call = F::Hook('Login.Before', $Call);
        $Call = F::Hook('beforeLoginDo', $Call);

        $AuthenticationBackends = F::Dot($Call, 'User.Login.Authentication.Backends');
        foreach ($AuthenticationBackends as $Backend) {
            $Call = F::Apply('Security.Authentication.Backend.' . $Backend, null, $Call);
        }

        $Call = F::Hook('afterLoginDo', $Call);
        $Call = F::Hook('Login.After', $Call);

        return $Call;
    });

    setFn('Identificate', function ($Call) {
        $Call = F::Hook('Identificate.Before', $Call);
        $Call = F::Hook('beforeIdentificate', $Call);

        $Call = F::Apply('Security.Authentication.Backend.' . $Call['Backend'], null, $Call);

        $Call['Layouts'][] = [
            'Scope' => 'User/Login',
            'ID' => isset($Call['Session']['User']['ID']) ? 'Logged' : 'Guest'
        ];

        $Call = F::Hook('afterIdentificate', $Call);
        $Call = F::Hook('Identificate.After', $Call);

        return $Call;
    });

    setFn('Authenticate', function ($Call) {
        $Call = F::Hook('Authenticate.Before', $Call);
        $Call = F::Hook('beforeAuthenticate', $Call);

        $Call = F::Apply('Security.Authentication.Backend.' . $Call['Backend'], null, $Call);

        if (isset($Call['Errors'])) {
            if (is_array($Call['Errors'])) {
                // Errors will be rendered by template
                F::Log('Authentication backend returned some errors', LOG_NOTICE, ['Session', 'Security']);
            }
        } else {
            if (($Candidate = F::Dot($Call, 'Candidate')) == null) {
                F::Log(
                    'Authentication backend went without errors, but there is no logged user.',
                    LOG_ERR
                );
                $Call = F::Hook('Authenticating.Failed', $Call); // FIXME Deprecate
                $Call = F::Hook('Authenticate.Failure', $Call);
            } else {
                $Call = F::Apply(null, 'Set.TTL', $Call);

                $Call = F::Apply(
                    'Session',
                    'Write',
                    $Call,
                    [
                        'Session Data' =>
                            [
                                'User' => $Candidate['ID']
                            ]
                    ]
                );

                if (F::Dot($Call, 'Session.User.ID') == $Candidate['ID']) {
                    F::Log(
                        'User *' . $Candidate['ID'] . '* is authenticated',
                        LOG_NOTICE,
                        ['Session', 'Security']
                    );

                    $Call = F::Hook('afterAuthenticate', $Call); // Deprecate
                    $Call = F::Hook('Authenticate.Success', $Call);
                } else {
                    F::Log(
                        'User is not authenticated',
                        LOG_NOTICE,
                        ['Session', 'Security']
                    );
                }
            }
        }

        $Call = F::Hook('Authenticate.After', $Call);

        return $Call;
    });

    setFn('Annulate', function ($Call) {
        $Call = F::Hook('Authenticate.Before', $Call);
        $Call = F::Hook('beforeAnnulate', $Call);

        $Call = F::Apply('Security.Authentication.Backend.' . $Call['Mode'], null, $Call);

        $Call['Layouts'][] =
            [
                'Scope' => 'User/Login',
                'ID' => isset($Call['Session']['User']['ID']) ? 'Logged' : 'Guest'
            ];

        $Call = F::Hook('afterAnnulate', $Call);
        $Call = F::Hook('Annulate.After', $Call);

        return $Call;
    });

    setFn('Set.TTL', function ($Call) {
        if (isset($Call['Request']['Remember'])) {
            $Call['TTL'] = F::Dot($Call, 'User.Login.Authentication.TTL.Long');
        } else {
            $Call['TTL'] = F::Dot($Call, 'User.Login.Authentication.TTL.Short');
        }

        F::Log(
            'TTL is *' . $Call['TTL'] . '*',
            LOG_INFO,
            ['Session', 'Security']
        );

        return $Call;
    });
