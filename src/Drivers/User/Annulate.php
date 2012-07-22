<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Do', function ($Call)
    {
        F::Run('Security.Auth', 'Detach', $Call);

        $Call = F::Hook('Annulate.Success', $Call);

        return $Call;

    });