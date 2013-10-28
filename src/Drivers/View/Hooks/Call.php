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
                        $Matched = '#';

                    if (($Matched === false) || ($Matched === 0))
                        $Matched = '0';

                    $Call['Value'] = str_replace($Pockets[0][$IX], F::Live($Matched), $Call['Value']);
                }
                else
                {
                    F::Log(
                        'Call to *'.$Match.'* cannot resolved at '.$Call['Scope'].':'.$Call['ID'],
                        $Call['Verbosity']['Calltag']['Unresolved']);

                    $Call['Value'] = str_replace($Pockets[0][$IX], '', $Call['Value']);
                }
            }
        }

        if (preg_match_all('@<call/>@SsUu', $Call['Value'], $Pockets))
            $Call['Value'] = str_replace($Pockets[0],
                '<pre>'
                .htmlentities(
                    json_encode($Call,
                        JSON_PRETTY_PRINT
                        | JSON_UNESCAPED_UNICODE
                        | JSON_UNESCAPED_SLASHES))
                .'</pre>',
                $Call['Value']);

        return $Call;
    });