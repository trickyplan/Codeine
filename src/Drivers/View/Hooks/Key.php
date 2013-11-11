<?php

    /* Codeine
     * @author BreathLess
     * @description <k> tag 
     * @package Codeine
     * @version 7.x
     */

    setFn('Parse', function ($Call)
    {
        if (preg_match_all('@<k>(.*)</k>@SsUu', $Call['Value'], $Call['Parsed']) && isset($Call['Data']))
        {
            $Call['Parsed'][1] = array_unique($Call['Parsed'][1]);

            foreach ($Call['Parsed'][1] as $IX => $Match)
            {
                if (mb_strpos($Match, ',') !== false)
                    $Match = explode(',', $Match);
                else
                    $Match = [$Match];

                $Matched = '';

                foreach ($Match as $CMatch)
                {
                    $Matched = F::Live(F::Dot($Call['Data'], $CMatch));

                    if (!empty($Matched))
                    {
                        if ((array) $Matched === $Matched)
                            $Matched = array_shift($Matched);

                        if (($Matched === false) or ($Matched === 0) )
                            $Matched = '0';

                        break;
                    }
                }

                if (!is_array($Matched))
                    $Call['Value'] = str_replace($Call['Parsed'][0][$IX], $Matched, $Call['Value']);
                else
                    $Call['Value'] = str_replace($Call['Parsed'][0][$IX], '{}', $Call['Value']);
            }
        }

        return $Call;
    });