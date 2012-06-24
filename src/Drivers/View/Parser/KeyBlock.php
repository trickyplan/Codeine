<?php

    /* Codeine
     * @author BreathLess
     * @description <k> tag 
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Parse', function ($Call)
    {
        if (preg_match_all('@<block>(.*)<k>(.*)<\/k>(.*)<\/block>@SsUu', $Call['Value'], $Pockets))
        {
            foreach ($Pockets[2] as $IX => $Match)
            {
                $Output = '';

                if (($Matched = F::Dot($Call['Data'], $Match)) !== null)
                {
                    if (is_array($Call['Data'][$Match]))
                        foreach($Call['Data'][$Match] as $cMatch)
                            $Output.= $Pockets[1][$IX].($cMatch).$Pockets[3][$IX];
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