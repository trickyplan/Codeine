<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeLoginDo', $Call);

            foreach ($Call['Auth Modes'] as $Mode)
                $Call = F::Apply('Security.Auth.'.$Mode , null, $Call);

        $Call = F::Hook('afterLoginDo', $Call);

        return $Call;
    });

    setFn('Identificate', function ($Call)
    {
        $Call = F::Hook('beforeIdentificate', $Call);

        $Call = F::Apply('Security.Auth.'.$Call['Mode'], null, $Call);

        $Call['Layouts'][] = [
            'Scope' => 'User.Login',
            'ID' => isset($Call['Session']['User']['ID'])? 'Logged': 'Guest'];

        $Call = F::Hook('afterIdentificate', $Call);

        return $Call;
    });

    setFn('Authenticate', function ($Call)
    {
        $Call = F::Hook('beforeAuthenticate', $Call);

        $Call = F::Apply('Security.Auth.'.$Call['Mode'], null, $Call);

        if (!empty($Call['User']))
        {
            if (isset($Call['Request']['Remember']))
                $Call['TTL'] = $Call['TTLs']['Long'];

            $Call = F::Apply('Session', 'Write', $Call, ['Session Data' => ['User' => $Call['User']['ID']]]);
            
            if ($Call['Session']['User']['ID'] == $Call['User']['ID'])
            {
                F::Log('User authenticated '.$Call['User']['ID'], LOG_INFO, 'Security');
                
                $Call = F::Hook('afterAuthenticate', $Call);
            }
            else
                F::Log('User is not authenticated', LOG_INFO, 'Security');
        }
        else
        {
            $Call = F::Hook('Authenticating.Failed', $Call);
            F::Log('Authentification failed', LOG_INFO, 'Security');
        }

        return $Call;
    });

    setFn('Annulate', function ($Call)
    {
        $Call = F::Hook('beforeAnnulate', $Call);

            $Call = F::Apply('Security.Auth.'.$Call['Mode'], null, $Call);

            $Call['Layouts'][] = [
                'Scope' => 'User.Login',
                'ID' => isset($Call['Session']['User']['ID'])? 'Logged': 'Guest'];

        $Call = F::Hook('afterAnnulate', $Call);

        return $Call;
    });