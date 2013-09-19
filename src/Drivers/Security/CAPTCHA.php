<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6,2
     */

    setFn('Widget', function ($Call)
    {
        return F::Run($Call['CAPTCHA Service'], 'Widget', $Call);
    });

    setFn('Check', function ($Call)
    {
        if (!isset($Call['CAPTCHA']['Bypass']))
            if (!F::Run($Call['CAPTCHA Service'], 'Check', $Call))
            {
                F::Log('CAPTCHA Failed from IP '.$Call['IP'], LOG_ERR, 'Security');
                $Call['Failure'] = true;
                $Call = F::Hook('CAPTCHA.Failed', $Call);
            }

        return $Call;
    });