<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Check', function ($Call)
    {
        foreach ($Call['Rules'] as $Rule)
            if (($Rule['Call'] == array_intersect_assoc($Rule['Call'], $Call)) && $Rule['Weight'] > $Call['Weight'])
            {
                $Call['Decision'] = $Rule['Decision'];
                $Call['Weight'] = $Rule['Weight'];
            }

        return $Call;
     });