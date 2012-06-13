<?php

    /* Codeine
     * @author BreathLess
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Do', function ($Call)
    {
        $Call =  F::Hook('beforeEntityDelete', $Call);

            $Call = F::Run('Entity', 'Delete', $Call);

        $Call = F::Hook('afterEntityDelete', $Call);

        return $Call;
    });