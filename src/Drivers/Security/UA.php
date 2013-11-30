<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Filter', function ($Call)
    {
        $Decision = true;

        if (isset($Call['Security']['UA']['Bad']))
            foreach ($Call['Security']['UA']['Bad'] as $Bad)
            {
                if (preg_match('/'.$Bad.'/SsUui', $Call['UA']))
                {
                    F::Log('Bad UA Detected: '.$Call['UA'].' from IP '.F::Live($Call['IP']), LOG_WARNING, 'Security');
                    $Decision = false;
                }
            }

        $Call = $Decision?
            F::Hook('Security.UA.Allowed', $Call):
            F::Hook('Security.UA.Denied', $Call);

        return $Call;
    });