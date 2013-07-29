<?php

    /* Codeine
     * @author BreathLess
     * @description <k> tag 
     * @package Codeine
     * @version 7.x
     */

    setFn('Parse', function ($Call)
    {
        if (preg_match_all('@<treekey(.*)>(.*)</treekey>@SsUu', $Call['Value'], $Call['Parsed']))
        {
            $Call['Parsed'][1] = array_unique($Call['Parsed'][1]);

            foreach ($Call['Parsed'][2] as $IX => $Match)
            {
                $Root = simplexml_load_string('<forkey'.$Call['Parsed'][1][$IX].'></forkey>');
                $Output = '';
                $Key = (string) $Root->attributes()->key;

                if (($Matched = F::Live(F::Dot($Call['Data'], $Key))) !== null)
                {
                    if (is_array($Matched))
                    {
                        $Rows = [];

                        F::Map($Matched,
                            function ($Key, $Value, $Data, $NewFullKey, $Array) use (&$Rows)
                            {
                                if (!is_array($Value))
                                    $Rows[] = [substr($NewFullKey,1), $Value];
                            }
                        );

                        foreach ($Rows as $Row)
                            $Output .= strtr($Match, ['<treek/>' => $Row[0], '<treev/>' => $Row[1]]);

                        $Call['Value'] = str_replace($Call['Parsed'][0][$IX], $Output, $Call['Value']);
                    }
                }
                else
                    $Call['Value'] = str_replace($Call['Parsed'][0][$IX], '', $Call['Value']);
            }
        }

        return $Call;
    });