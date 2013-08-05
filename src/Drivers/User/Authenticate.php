<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeAuthenticateGet', $Call);

        $Call['Layouts'][] = ['Scope' => 'User.Authenticate', 'ID' => isset($Call['Session']['User']['ID'])? 'Logged': 'Guest'];

        $Call = F::Hook('afterAuthenticateGet', $Call);
        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeAuthenticatePost', $Call);

        $Call = F::Run('Security.Auth.System.'.$Call['Mode'], 'Authenticate', $Call);

        if (!empty($Call['User']))
        {
            if (isset($Call['Request']['Remember']))
                $Call['TTL'] = $Call['TTLs']['Long'];

            $Call = F::Hook('beforeAuthenticate', $Call);

            if (!isset($Call['Failure']))
            {
                $Call['Session'] = F::Run('Session', 'Write', $Call, ['Data' => ['User' => $Call['User']['ID']]]);

                if ($Call['Session']['User'] == $Call['User'])
                    $Call = F::Hook('afterAuthenticatePost', $Call);

            }
        }
        else
        {
            $Call = F::Hook('Authenticating.Failed', $Call);
            F::Log('Authentification failed', LOG_INFO);
        }

        return $Call;
    });