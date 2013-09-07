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
                    if (($Matched = F::Live(F::Dot($Call['Data'], $CMatch))) !== null)
                    {
                        if ((array) $Matched === $Matched)
                            $Matched = array_shift($Matched);

                        if (($Matched === false) or ($Matched === 0) )
                            $Matched = '0';

                        break;
                    }
                }

                $Call['Value'] = str_replace($Call['Parsed'][0][$IX], $Matched, $Call['Value']);
            }
        }

        return $Call;
    });