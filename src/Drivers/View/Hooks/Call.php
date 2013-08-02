<?php

    /* Codeine
     * @author BreathLess
     * @description <k> tag 
     * @package Codeine
     * @version 7.x
     */

    setFn('Parse', function ($Call)
    {
        if (preg_match_all('@<call>(.*)</call>@SsUu', $Call['Value'], $Pockets))
        {
            foreach ($Pockets[1] as $IX => $Match)
            {
                if (($Matched = F::Dot($Call, $Match)) !== null)
                {
                    $Matched = F::Live($Matched);

                    if (is_array($Matched))
                        $Matched = implode(' ', $Matched);

                    if (($Matched === false) || ($Matched === 0))
                        $Matched = '0';

                    $Call['Value'] = str_replace($Pockets[0][$IX], F::Live($Matched), $Call['Value']);
                }
            }
        }

        return $Call;
    });