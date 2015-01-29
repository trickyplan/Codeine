<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description <k> tag 
     * @package Codeine
     * @version 8.x
     */

    setFn('Parse', function ($Call)
    {
        $Call['Parsed'] = F::Run('Text.Regex', 'All',
        [
            'Pattern' => $Call['Call Pattern'],
            'Value' => $Call['Output']
        ]);

        if ($Call['Parsed'] && isset($Call['Data']))
        {
            $Call['Parsed'][0] = array_unique($Call['Parsed'][0]);
            $Call['Parsed'][1] = array_unique($Call['Parsed'][1]);

            foreach ($Call['Parsed'][1] as $IX => $Match)
            {
                if (($Matched = F::Dot($Call, $Match)) !== null)
                {
                    if (is_array($Matched))
                        $Matched = j($Matched);

                    if (($Matched === false) || ($Matched === 0))
                        $Matched = '0';

                    $Call['Parsed'][1][$IX] = F::Live($Matched);
                }
                else
                {
                    F::Log(
                        'Call to *'.$Match.'* cannot resolved at '.$Call['Scope'].':'.$Call['ID'],
                        $Call['Verbosity']['Calltag']['Unresolved']);

                    $Call['Parsed'][1][$IX] = '';
                }
            }

            $Call['Output'] = str_replace($Call['Parsed'][0], $Call['Parsed'][1], $Call['Output']);
        }

        if (preg_match_all('@<call/>@SsUu', $Call['Output'], $Pockets))
            $Call['Output'] = str_replace($Call['Parsed'][0],
                '<pre>'
                .htmlentities(
                    j($Call,
                        JSON_PRETTY_PRINT
                        | JSON_UNESCAPED_UNICODE
                        | JSON_UNESCAPED_SLASHES))
                .'</pre>',
                $Call['Output']);

        return $Call;
    });