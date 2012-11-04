<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Run('Security.Auth', 'Detach', $Call);

        $Call = F::Hook('Annulate.Success', $Call);

        return $Call;

    });