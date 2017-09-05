<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    self::_loadSource('Entity.Control');

    setFn('Login', function ($Call)
    {
        return F::Run('User.Login', 'Do', $Call);
    });

    setFn('Rights', function ($Call)
    {
        return F::Run('User.Rights', 'Do', $Call);
    });
    
    setFn('Impersonate', function ($Call)
    {
        $Call = F::Apply('Entity', 'Load', $Call, ['Entity' => 'User']);

        $Call = F::Apply('Session', 'Write', $Call,
            [
            'Session Data' =>
                [
                    'Secondary' => $Call['Where'],
                    'User' => $Call['Session']['User']['ID']
                ]
            ]);
        F::Log('User '.$Call['Session']['User']['ID'].' logged in '.$Call['Where'], LOG_INFO, 'Security');
        
        $Call = F::Hook('afterUserLoginDo', $Call);

        return $Call;
    });