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

        $Call = F::Run('Session', 'Annulate', $Call);

        $Call = F::Hook('afterUserLogout', $Call);

        return $Call;
    });