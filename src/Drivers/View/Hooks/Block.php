<?php

    /* Codeine
     * @author BreathLess
     * @description <k> tag 
     * @package Codeine
     * @version 7.x
     */

    setFn('Parse', function ($Call)
    {
        $Call = F::Apply(null, 'Key', $Call);
        $Call = F::Apply(null, 'Call', $Call);

        return $Call;
    });

    setFn('Call', function ($Call)
    {
        if (preg_match_all('@<block>(.*)<call>(.*)<\/call>(.*)<\/block>@SsUu', $Call['Value'], $Pockets))
        {
            foreach ($Pockets[2] as $IX => $Match)
            {
                if (($Matched = F::Live(F::Dot($Call, $Match))) !== null)
                {
                    $Output = '';

                    if ($DotMatched = F::Live(F::Dot($Call, $Match)))
                    {
                        if (is_array($DotMatched))
                        {
                            sort($DotMatched);
                            foreach($DotMatched as $ICV => $cMatch)
                                $Output.= str_replace('<#/>',
                                    $ICV,
                                    str_replace('<call>'.$Match.'</call>', $cMatch,$Pockets[1][$IX]).
                                    ($cMatch)
                                    .str_replace('<call>'.$Match.'</call>', $cMatch,$Pockets[3][$IX]));
                        }
                        else
                            $Output = str_replace('<#/>', '', $Pockets[1][$IX].($DotMatched).$Pockets[3][$IX]);
                    }

                    $Call['Value'] = str_replace($Pockets[0][$IX], $Output, $Call['Value']);
                }
                else
                    $Call['Value'] = str_replace($Pockets[0][$IX], '', $Call['Value']);
            }
        }

        return $Call;
    });


    setFn('Key', function ($Call)
    {
        if (preg_match_all('@<block>(.*)<k>(.*)<\/k>(.*)<\/block>@SsUu', $Call['Value'], $Pockets))
        {
            foreach ($Pockets[2] as $IX => $Match)
            {
                if (isset($Call['Data']) && ($Matched = F::Live(F::Dot($Call['Data'], $Match))) !== null)
                {
                    $Output = '';

                    if ($DotMatched = F::Live(F::Dot($Call['Data'], $Match)))
                    {
                        if (is_array($DotMatched))
                        {
                            sort($DotMatched);
                            foreach($DotMatched as $ICV => $cMatch)
                                if (!is_array($cMatch))
                                    $Output.= str_replace('<#/>',
                                    $ICV,
                                    str_replace('<k>'.$Match.'</k>', $cMatch,$Pockets[1][$IX]).
                                    ($cMatch)
                                    .str_replace('<k>'.$Match.'</k>', $cMatch,$Pockets[3][$IX]));
                        }
                        else
                            $Output = str_replace('<#/>', '', $Pockets[1][$IX].($DotMatched).$Pockets[3][$IX]);
                    }

                    $Call['Value'] = str_replace($Pockets[0][$IX], $Output, $Call['Value']);
                }
                else
                    $Call['Value'] = str_replace($Pockets[0][$IX], '', $Call['Value']);
            }
        }

        return $Call;
    });