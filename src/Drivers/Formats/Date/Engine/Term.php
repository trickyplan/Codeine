<?php

    /* Codeine
     * @author BreathLess
     * @description Date() engine 
     * @package Codeine
     * @version 7.x
     */

    setFn('Format', function ($Call)
    {
        if (isset($Call['Value']))
        {
            foreach ($Call['Period']['Units'] as $Period => $Value)
                if ($Call['Value'] >= $Value)
                {
                    $Units = floor($Call['Value']/$Value);
                    $Output[] = $Units.' <l>Formats.Period:'.$Period.'.'.($Units%20).'</l>';
                    $Call['Value'] -= $Units*$Value;
                }

            return implode(' ', array_slice($Output,0,$Call['Period']['Format']));
        }
    });