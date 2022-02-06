<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call)
    {
        // FIXME Combine with Key
        foreach ($Call['Parsed']['Value'] as $IX => $Variable)
        {
            if (str_contains($Variable, ','))
                $Variable = explode(',', $Variable);
            else
                $Variable = [$Variable];

            $Value = '';

            foreach ($Variable as $CMatch)
            {
                $Value = F::Live(F::Dot($Call, $CMatch));

                if ($Value === null)
                {
                    if (isset($Call['Parsed']['Options'][$IX]['null']))
                        $Value = $Call['Parsed']['Options'][$IX]['null'];
                    else
                        $Value = 'null';
                }
                else
                {
                    if ((array) $Value === $Value)
                    {
                        if (isset($Call['Parsed']['Options'][$IX]['json']))
                            $Value = j($Value);
                        else
                            $Value = array_shift($Value);
                    }

                    if ($Value === 0)
                        $Value = '0';

                    if ($Value === false)
                        $Value = 'false';

                    if ($Value === true)
                        $Value = 'true';

                    break;
                }
            }

            if (is_array($Value))
                $Value = array_pop($Value);

            $Call['Replace'][$Call['Parsed']['Match'][$IX]] = $Value;
        }

        return $Call;
     });