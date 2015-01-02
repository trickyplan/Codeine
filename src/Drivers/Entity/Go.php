<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call);

        $Call = F::Hook('beforeEntityGo', $Call);

            $Call['Data'] = F::Run('Entity', 'Read', $Call);
            $Call = F::Run('System.Interface.HTTP', 'Remote Redirect', $Call, ['Location' => $Call['Data']['URL']]);

        $Call = F::Hook('afterEntityGo', $Call);

        return $Call;
    });