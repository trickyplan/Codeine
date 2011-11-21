<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 6:17
     */

    self::Fn('Route', function ($Call)
    {
        if (strpos($Call['Value'], '?'))
            list($Call['Value']) = explode('?', $Call['Value']);

        // TODO Error: Not found Regex Table

        foreach ($Call['Regex'] as $Rule)
            if (preg_match($Rule['Match'], $Call['Value'], $Matches))
            {
                foreach ($Rule['Replace'] as $Key => $Value)
                    $Rule['Call'][$Key] = $Matches[$Value];

                return $Rule['Call'];
            }

        return null;
    });
