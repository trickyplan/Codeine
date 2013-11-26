<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {

        $Call = F::Hook('beforeUserLogout', $Call);

            $Call = F::Apply('Session', 'Annulate', $Call);

            F::Log('User '.$Call['Session']['User']['ID'].' logged off', LOG_INFO, 'Security');

        $Call = F::Hook('afterUserLogout', $Call);

        return $Call;
    });