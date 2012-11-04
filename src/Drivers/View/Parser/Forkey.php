<?php

    /* Codeine
     * @author BreathLess
     * @description <k> tag 
     * @package Codeine
     * @version 7.x
     */

    setFn('Parse', function ($Call)
    {
        if (preg_match_all('/<forkey(.*)>(.*)<\/forkey>/SsUu', $Call['Value'], $Call['Parsed']))
        {
            foreach ($Call['Parsed'][0] as $IX => $Match)
            {
                $Root = simplexml_load_string('<forkey'.$Call['Parsed'][1][$IX].'></forkey>');

                $Output = '';

                $Key = (string) $Root->attributes()->key;

                $Data = F::Dot($Call['Data'], $Key);

                foreach ($Call['Data'][$Key] as $KeyIndex => $Value)
                {
                    $Output[$KeyIndex] = $Call['Parsed'][2][$IX];

                    $Value['#'] = $KeyIndex;

                    if (preg_match_all('@<subvalue/>@SsUu', $Output[$KeyIndex], $Pockets))
                        $Output[$KeyIndex] = str_replace($Pockets[0][1], $Value, $Output[$KeyIndex]);

                    if (preg_match_all('@<block>(.*)<subk>(.*)<\/subk>(.*)<\/block>@SsUu', $Output[$KeyIndex], $Pockets))
                    {

                        foreach ($Pockets[2] as $IC => $Subkey)
                            if (($Data = F::Dot($Value, $Subkey))!== null)
                            {
                                $SubOutput = '';
                                $Data = (array) $Data;

                                foreach ($Data as $SubData)
                                    $SubOutput.= $Pockets[1][$IX].$SubData.$Pockets[3][$IX];

                                $Output[$KeyIndex] = str_replace($Pockets[0][$IC], $SubOutput, $Output[$KeyIndex]);

                            }
                            else
                                $Output[$KeyIndex] = str_replace($Pockets[0][$IC], '', $Output[$KeyIndex]);
                    }

                    if (preg_match_all('/<subk>(.*)<\/subk>/SsUu', $Output[$KeyIndex], $Pockets))
                    {
                        foreach ($Pockets[1] as $IC => $Subkey)
                            if (($Data = F::Dot($Value, $Subkey))!== null)
                                $Output[$KeyIndex] = str_replace($Pockets[0][$IC], $Data, $Output[$KeyIndex]);
                            else
                                $Output[$KeyIndex] = str_replace($Pockets[0][$IC], 0, $Output[$KeyIndex]);
                    }
                }

                $Call['Value'] = str_replace($Match, implode('',$Output), $Call['Value']);
            }
        }

        return $Call['Value'];
    });