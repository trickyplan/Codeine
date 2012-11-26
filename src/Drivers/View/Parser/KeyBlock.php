<?php

    /* Codeine
     * @author BreathLess
     * @description <k> tag 
     * @package Codeine
     * @version 7.x
     */

    setFn('Parse', function ($Call)
    {
        if (preg_match_all('@<block>(.*)<k>(.*)<\/k>(.*)<\/block>@SsUu', $Call['Value'], $Pockets))
        {
            foreach ($Pockets[2] as $IX => $Match)
            {
                $Output = '';

                if (($Matched = F::Live(F::Dot($Call['Data'], $Match))) !== null)
                {
                    if (($Matched === false) || ($Matched === 0))
                        $Matched = '0';

                    if ($DotMatched = F::Live(F::Dot($Call['Data'], $Match)))
                    {
                        if (is_array($DotMatched))
                            foreach($DotMatched as $ICV => $cMatch)
                                $Output.= str_replace('<#/>', $ICV, str_replace('<k>'.$Match.'</k>', $cMatch,$Pockets[1][$IX]).
                                    ($cMatch)
                                    .str_replace('<k>'.$Match.'</k>', $cMatch,$Pockets[3][$IX]));
                        else
                            $Output = $Pockets[1][$IX].($DotMatched).$Pockets[3][$IX];
                    }

                    $Call['Value'] = str_replace($Pockets[0][$IX], $Output, $Call['Value']);
                    $Output = '';
                }
                else
                    $Call['Value'] = str_replace($Pockets[0][$IX], '', $Call['Value']);
            }
        }

        return $Call['Value'];
    });