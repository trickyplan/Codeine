<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Calculate', function ($Call)
    {
        $Value = 0;

        foreach ($Call['Antispam']['Detectors'] as $Detector)
            $Value+= F::Run('Security.Antispam.'.$Detector, null, $Call,
                ['Value' => $Call['Data']['Body']]);

        return $Value >= $Call['Antispam']['Threshold'];
    });