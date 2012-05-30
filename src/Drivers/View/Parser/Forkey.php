<?php

    /* Codeine
     * @author BreathLess
     * @description <k> tag 
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Parse', function ($Call)
    {
        if (preg_match_all('/<forkey(.*)>(.*)<\/forkey>/SsUu', $Call['Value'], $Call['Parsed']))
        {
            foreach ($Call['Parsed'][0] as $IX => $Match)
            {
                $Root = simplexml_load_string($Match);

                $Output = '';

                $Key = (string) $Root->attributes()->key;

                foreach ($Call['Data'][$Key] as $KeyIndex => $Value)
                {
                    $Output[$KeyIndex] = $Call['Parsed'][2][$IX];

                    if (preg_match_all('/<subk>(.*)<\/subk>/SsUu', $Output[$KeyIndex], $Pockets))
                    {
                        foreach ($Pockets[1] as $IC => $Subkey)
                            if (($Data = F::Dot($Value, $Subkey))!== null)
                                $Output[$KeyIndex] = str_replace($Pockets[0][$IC], $Data, $Output[$KeyIndex]);
                    }
                }

                $Call['Value'] = str_replace($Match, implode('',$Output), $Call['Value']);
            }
        }

        return $Call['Value'];
    });