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

        F::Log('Locale is *'.$Call['Locale'].'*', LOG_INFO);

        return $Call['Locale'];
    });