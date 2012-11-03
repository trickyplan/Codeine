<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.x
     * @date 31.08.11
     * @time 6:17
     */

    self::setFn('Route', function ($Call)
    {
        if (strpos($Call['Run'], '?'))
            list($Call['Run']) = explode('?', $Call['Run']);

        // TODO Error: Not found Regex Table
        $Decision = null;
        $Weight = -255;

        if (isset($Call['Regex']))
            {
                foreach ($Call['Regex'] as $Name => $Rule)
                {
                    if (!isset($Rule['Weight']))
                        $Rule['Weight'] = 0;

                    if ($Rule['Weight'] > $Weight)
                    {
                        if (preg_match ($Rule['Match'], $Call['Run'], $Matches))
                        {
                            $Rule = F::Map($Rule, function (&$Key, &$Value, $Data, $FullKey, &$Array) use ($Matches)
                            {
                                if (is_scalar($Key) && substr($Key, 0, 1) == '$')
                                {
                                    if (isset($Matches[substr($Key, 1)]))
                                    {
                                        unset($Array[$Key]);
                                        $Key = $Matches[substr($Key, 1)];
                                    }
                                }

                                if (is_scalar($Value) && substr($Value, 0, 1) == '$')
                                {
                                    if (isset($Matches[substr($Value, 1)]))
                                        $Value = $Matches[substr($Value, 1)];
                                }
                            });

                            F::Log('Regex router rule '.$Name.' matched', LOG_DEBUG);

                            $Weight = $Rule['Weight'];
                            $Decision = $Rule;
                            $Selected = $Name;
                        }
                    }
                }
            }
        else
            die('Regex routes table corrupted'); // FIXME

        if (isset($Selected))
            F::Log('Regex router rule '.$Selected.' selected', LOG_INFO);
        else
            F::Log('No one regex rule selected', LOG_INFO);

        $Call['Run'] = $Decision;

        return $Call;
    });
