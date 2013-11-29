<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        return F::Run(null, $Call['HTTP']['Method'], $Call);
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeAuthenticateGet', $Call);

        $Call['Layouts'][] = [
            'Scope' => 'User.Authenticate',
            'ID' => isset($Call['Session']['User']['ID'])? 'Logged': 'Guest'];

        $Call = F::Hook('afterAuthenticateGet', $Call);

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeAuthenticatePost', $Call);

        $Call = F::Apply('Security.Auth.System.'.$Call['Mode'], 'Authenticate', $Call);

        if (!empty($Call['User']))
        {
            if (isset($Call['Request']['Remember']))
                $Call['TTL'] = $Call['TTLs']['Long'];

            $Call = F::Apply('Session', 'Write', $Call, ['Data' => ['User' => $Call['User']['ID']]]);

            if ($Call['Session']['User'] == $Call['User']['ID'])
            {
                $Call = F::Hook('afterAuthenticatePost', $Call);
                F::Log('User authorized '.$Call['User']['ID'], LOG_INFO, 'Security');
            }
            else
                F::Log('User is not authorized', LOG_INFO, 'Security');
        }
        else
        {
            $Call = F::Hook('Authenticating.Failed', $Call);
            F::Log('Authentification failed', LOG_INFO, 'Security');
        }

        return $Call;
    });