<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Filter', function ($Call)
    {
        $Decision = true;

        if (isset($Call['Security']['Agent']['Bad']))
            foreach ($Call['Security']['Agent']['Bad'] as $Bad)
            {
                if (preg_match('/'.$Bad.'/SsUui', $Call['HTTP']['Agent']))
                {
                    F::Log('Bad User Agent Detected: '.$Call['HTTP']['Agent'].' from IP '.F::Live($Call['HTTP']['IP']), LOG_WARNING, 'Security');
                    $Decision = false;
                }
            }

        $Call = $Decision?
            F::Hook('Security.User Agent.Allowed', $Call):
            F::Hook('Security.User Agent.Denied', $Call);

        return $Call;
    });