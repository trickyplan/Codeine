<?php

    /* Codeine
     * @author BreathLess
     * @description Date() engine 
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Format', function ($Call)
    {
        if (isset($Call['Value']))
        {
            $Periods = ['min' => 60, 'hrs' => 3600];

            foreach ($Periods as $Period => $Value)
                if ($Call['Value'] >= $Value)
                {
                    $Parts[$Period] = floor($Call['Value']/$Value);
                    $Call['Value'] -= $Parts[$Period]*$Value;
                }

            $Parts['sec'] = $Call['Value'];

            list($Key, $Value) = each($Parts);

            return $Value.' '.$Key.'';
        }
    });