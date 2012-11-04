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
        if (isset($Call['CAPTCHA']['Bypass']))
            return $Call;
        else
            return F::Run($Call['CAPTCHA Service'], 'Check', $Call);
    });