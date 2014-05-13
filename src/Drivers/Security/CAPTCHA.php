<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6,2
     */

    setFn('Widget', function ($Call)
    {
        return F::Apply($Call['Modes'][$Call['Mode']], 'Widget', $Call);
    });

    setFn('Check', function ($Call)
    {
        if (!isset($Call['CAPTCHA']['Bypass']))
            if (!F::Run($Call['Modes'][$Call['Mode']], 'Check', $Call))
            {
                F::Log('CAPTCHA Failed from IP '.F::Live($Call['HTTP']['IP']), LOG_ERR, 'Security');
                $Call['Failure'] = true;
                $Call = F::Hook('CAPTCHA.Failed', $Call);
            }

        return $Call;
    });