<?php

    /* Codeine
     * @author BreathLess
     * @description <k> tag 
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Parse', function ($Call)
    {
        if (preg_match_all('@<array>(.*)<k>(.*)<\/k>(.*)<\/array>@SsUu', $Call['Value'], $Pockets))
        {
            foreach ($Pockets[2] as $IX => $Match)
            {
                $Output = '';

                if (($Matched = F::Dot($Call['Data'], $Match)) !== null)
                {
                    if (($Matched === false) || ($Matched === 0))
                        $Matched = '0';

                    if (is_array($Call['Data'][$Match]))
                        foreach($Call['Data'][$Match] as $Key => $cMatch)
                            $Output.= str_replace('<arraykey/>', $Key, $Pockets[1][$IX].($cMatch).$Pockets[3][$IX]);
                    else
                        $Output = $Pockets[1][$IX].($Call['Data'][$Match]).$Pockets[3][$IX];

                    $Call['Value'] = str_replace($Pockets[0][$IX], $Output, $Call['Value']);
                }
                else
                    $Call['Value'] = str_replace($Pockets[0][$IX], '', $Call['Value']);
            }
        }

        return $Call['Value'];
    });