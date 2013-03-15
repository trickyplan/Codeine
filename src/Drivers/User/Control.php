<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::_loadSource('Entity.Control');

    setFn('Login', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call, ['Entity' => 'User']);

        $Call['Session'] = F::Run('Session', 'Write', $Call, ['Data' => ['Secondary' => $Call['Where']]]);

        $Call = F::Hook('afterLogin', $Call);

        return $Call;
    });

    setFn('Rights', function ($Call)
    {
        return F::Run('User.Rights', 'Do', $Call);
    });