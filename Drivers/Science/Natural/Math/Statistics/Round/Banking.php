<?php

    /* Codeine
     * @author BreathLess
     * @description Modified code
     * @package Codeine
     * @version 6.0
     * @origin http://boonedocks.net/code/bround-bsd.inc.phps
     */

    self::Fn('Round', function ($Call)
    {
        $Fuzz = 0.00001; // to deal with floating-point precision loss
        // OPTME

        $Roundup = 0; // amount to round up by

        $iSign = ($Call['Value']!=0.0) ? intval($Call['Value']/abs($Call['Value'])) : 1;
        $Call['Value'] = abs($Call['Value']);

        // get decimal digit in question and amount to right of it as a fraction
        $Working = $Call['Value'] * pow (10.0, $Call['Precision'] + 1) - floor($Call['Value'] * pow (10.0, $Call['Precision'])) * 10.0;

        $EvenOddDigit = floor ($Call['Value']*pow(10.0,$Call['Precision']))-floor($Call['Value']*pow (10.0, $Call['Precision']-1)) * 10.0;

        if (abs($Working - 5.0) < $Fuzz)
            $Roundup = ($EvenOddDigit & 1) ? 1 : 0;
        else
            $Roundup = ($Working>5.0) ? 1 : 0;

        return $iSign * ((floor ($Call['Value'] * pow (10.0, $Call['Precision']))+$Roundup)/pow (10.0, $Call['Precision']));
    });