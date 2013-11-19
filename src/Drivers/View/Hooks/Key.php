<?php

    /* Codeine
     * @author BreathLess
     * @description <k> tag 
     * @package Codeine
     * @version 7.x
     */

    setFn('Parse', function ($Call)
    {
        $Call['Parsed'] = F::Run('Text.Regex', 'All', $Call,
        [
            'Pattern' => $Call['Key Pattern']
        ]);

        if ($Call['Parsed'] && isset($Call['Data']))
        {
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

                    if (!empty($Matched))
                    {
                        if ((array) $Matched === $Matched)
                            $Matched = array_shift($Matched);

                        if (($Matched === false) or ($Matched === 0) )
                            $Matched = '0';

                        break;
                    }
                }

                if (is_array($Matched))
                    $Match = '{}';
                else
                    $Match = $Matched;
            }

            $Call['Value'] = str_replace($Call['Parsed'][0], $Call['Parsed'][1], $Call['Value']);
        }

        return $Call;
    });