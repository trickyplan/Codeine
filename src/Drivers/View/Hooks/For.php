<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description <k> tag 
     * @package Codeine
     * @version 8.x
     */

    setFn('Parse', function ($Call)
    {
        if (preg_match_all('/<for(.*)>(.*)<\/for>/SsUu', $Call['Value'], $Call['Parsed']))
        {
            foreach ($Call['Parsed'][0] as $IX => $Match)
            {
                $Root = simplexml_load_string('<for'.$Call['Parsed'][1][$IX].'></for>');
                $Key = (string) $Root->attributes()->call;
                $Inner = $Call['Parsed'][2][$IX];
                
                $Values = F::Dot($Call, $Key);
                
                foreach ($Values as $Key => $Value)
                {
                    $Iterated = $Inner;
                    $Iterated = str_replace('<for-key/>', $Key, $Iterated);
                    $Iterated = str_replace('<for-value/>', $Value, $Iterated);
                    $Output[] = $Iterated;
                }
                
                $Call['Value'] = str_replace($Match, implode('',$Output), $Call['Value']);
            }
        }

        return $Call;
    });