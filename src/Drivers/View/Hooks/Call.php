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
                'Value' => $Call[$Call['Value Key']]
            ]);

        if (empty($Call['Parsed']))
            ;
        else
        {
            $Call['Parsed'][0] = array_unique($Call['Parsed'][0]);
            $Call['Parsed'][1] = array_unique($Call['Parsed'][1]);

            foreach ($Call['Parsed'][1] as $IX => $Match)
            {
                F::Log('[DEPRECATED] *<codeine-variable>'.$Match.'</codeine-variable>* in '.($Call['Scope']?? '?').':'.($Call['ID']?? '?').' will be ousted soon. Use "variable/external" tag instead', LOG_WARNING, ['Developer', 'Deprecated']);
                if (str_contains($Match, ':'))
                {
                    list ($Options, $Key) = explode(':', $Match);
                    $Call = F::Dot($Call, $Options, F::loadOptions($Options));
                    $Match = $Options.'.'.$Key;
                }

                if (str_contains($Match, ','))
                {
                    $Submatches = explode(',', $Match);
                    foreach ($Submatches as $Submatch)
                        if (($Matched = F::Dot($Call, $Submatch)) !== null)
                        {
                            $Match = $Submatch;
                            break;
                        }
                }

                if (($Matched = F::Dot($Call, $Match)) !== null)
                {
                    if (is_array($Matched))
                        $Matched = j($Matched);

                    if (($Matched === false) || ($Matched === 0))
                        $Matched = '0';

                    $Call['Parsed'][1][$IX] = $Matched;
                    F::Log(
                        'Call to *'.$Match.'* resolved as '.$Call['Parsed'][1][$IX],
                        LOG_DEBUG);
                }
                else
                {
                    F::Log(
                        'Call to *'.$Match.'* cannot resolved',
                        $Call['Verbosity']['Calltag']['Unresolved']);

                    if (isset($Call['Remove empty']))
                        $Call['Parsed'][1][$IX] = '';
                    else
                        unset($Call['Parsed'][0][$IX], $Call['Parsed'][1][$IX]);
                }
            }
            $Call[$Call['Value Key']] = str_replace($Call['Parsed'][0], $Call['Parsed'][1], $Call[$Call['Value Key']]);
        }

        if (preg_match_all('@<call/>@SsUu', $Call[$Call['Value Key']], $Pockets))
            $Call[$Call['Value Key']] = str_replace($Call['Parsed'][0],
                '<pre>'
                .htmlentities(
                    j($Call,
                        JSON_PRETTY_PRINT
                        | JSON_UNESCAPED_UNICODE
                        | JSON_UNESCAPED_SLASHES))
                .'</pre>',
                $Call[$Call['Value Key']]);

        return $Call;
    });