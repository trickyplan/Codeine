<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call);

        $Call = F::Hook('beforeTouch', $Call);

        F::Run('Entity', 'Update', $Call, ['Data' => []]);

        $Call = F::Hook('afterTouch', $Call);

        return $Call;
    });