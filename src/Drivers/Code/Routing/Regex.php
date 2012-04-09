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

        foreach ($Call['Regex'] as $Rule)
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

                F::Run('IO', 'Write', array('Storage' => 'Developer', 'Level' => 'Info', 'Data' => 'Regex Router Match: '.$Rule['Match']));

                return $Rule;
            }

        return null;
    });
