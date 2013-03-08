<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call, ['Entity' => 'User']);

        $Call = F::Run('Security.Auth', 'Detach', $Call);

        $Call = F::Hook('afterLogout', $Call);

        return $Call;
    });