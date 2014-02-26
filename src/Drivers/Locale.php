<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Detect', function ($Call)
    {
        $Call['Locale'] = $Call['Locales']['Default'];

        foreach ($Call['Locales']['Detectors'] as $Detector)
            $Call = F::Apply('Locale.Detector.'.$Detector, null, $Call);

        if (in_array($Call['Locale'], $Call['Locales']['Allowed']))
            F::Log('Locale is allowed *'.$Call['Locale'].'*', LOG_INFO + 0.5);
        else
        {
            F::Log('Locale is not allowed *'.$Call['Locale'].'*', LOG_INFO);
            $Call['Locale'] = $Call['Locales']['Default'];
            F::Log('Locale is *'.$Call['Locale'].'* by default', LOG_INFO + 0.5);
        }

        F::Log('Locale is *'.$Call['Locale'].'*', LOG_INFO);

        return $Call['Locale'];
    });