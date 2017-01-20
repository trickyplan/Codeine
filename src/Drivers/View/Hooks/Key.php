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
            'Pattern' => $Call['Key Pattern'],
            'Value' => $Call['Value']
        ]);

        if ($Call['Parsed'] && isset($Call['Data']))
        {
            $Call['Parsed'][0] = array_unique($Call['Parsed'][0]);
            $Call['Parsed'][1] = array_unique($Call['Parsed'][1]);

            foreach ($Call['Parsed'][1] as $IX => &$Match)
            {
                if (mb_strpos($Match, ',') !== false)
                    $Match = explode(',', $Match);
                else
                    $Match = [$Match];

                $Matched = '';

                foreach ($Match as $CMatch)
                {
                    $Matched = F::Live(F::Dot($Call['Data'], $CMatch));

                    if (empty($Matched))
                        ;
                    else
                    {
                        if ((array) $Matched === $Matched)
                            $Matched = array_shift($Matched);

                        if (($Matched === false) or ($Matched === 0))
                            $Matched = '0';

                        break;
                    }
                }

                if (is_array($Matched))
                    $Match = array_pop($Matched);
                else
                    $Match = $Matched;
            }

            $Call['Value'] = str_replace($Call['Parsed'][0], $Call['Parsed'][1], $Call['Value']);

            $Call['Value'] = str_replace('<k2>', '<k>', $Call['Value']); // FIXME
            $Call['Value'] = str_replace('</k2>', '</k>', $Call['Value']); // FIXME

        }

        return $Call;
    });