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

        $Call = F::Run('Session', 'Write', $Call, ['Data' => ['Secondary' => $Call['Where']]]);

        $Call = F::Hook('afterLogin', $Call);

        return $Call;
    });