<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.2
     * @date 31.08.11
     * @time 6:17
     */

    self::setFn('Route', function ($Call)
    {
        if (strpos($Call['Run'], '?'))
            list($Call['Run']) = explode('?', $Call['Run']);

        // TODO Error: Not found Regex Table

        $Decision = null;
        $Weight = 0;

        foreach ($Call['Regex'] as $Name => $Rule)
            if (preg_match($Rule['Match'], $Call['Run'], $Matches))
            {
                $Rule['Call'] = F::Map($Rule['Call'], function ($Key, &$Value) use ($Matches)
                {
                    if (is_scalar($Value) && substr($Value, 0, 1) == '$')
                    {
                        if (isset($Matches[substr($Value, 1)]))
                            $Value = $Matches[substr($Value, 1)];
                    }
                });

                F::Log('Regex router rule '.$Name.' matched');

                if (!isset($Rule['Weight']))
                    $Rule['Weight'] = 0;

                if ($Rule['Weight'] >= $Weight)
                {
                    $Weight = $Rule['Weight'];
                    $Decision = $Rule;
                    $Selected = $Name;
                }
            }

        F::Log('Regex router rule '.$Selected.' selected');

        return $Decision;
    });
