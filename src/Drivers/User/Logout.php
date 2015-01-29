<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeUserLogout', $Call);

        if (isset($Call['Session']['User']['ID']))
        {
            F::Log('User '.$Call['Session']['User']['ID'].' logged off', LOG_INFO, 'Security');
            $Call = F::Apply('Session', 'Annulate', $Call);
        }

        $Call = F::Hook('afterUserLogout', $Call);

        return $Call;
    });