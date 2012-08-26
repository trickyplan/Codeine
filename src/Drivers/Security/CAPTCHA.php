<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6,2
     */

    self::setFn('Widget', function ($Call)
    {
        return F::Run($Call['CAPTCHA Service'], 'Widget', $Call);
    });

    self::setFn('Check', function ($Call)
    {
        if (isset($Call['CAPTCHA']['Bypass']))
            return $Call;
        else
            return F::Run($Call['CAPTCHA Service'], 'Check', $Call);
    });