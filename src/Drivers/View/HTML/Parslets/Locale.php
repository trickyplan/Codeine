<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Layout Parslet
     * @package Codeine
     * @version 8.x
     */

    setFn ('Parse', function ($Call)
    {
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            if (str_contains($Match, ','))
                $Matches = explode(',', $Match);
            else
                $Matches = [$Match];
                
             $Translation = F::Run('Locale', 'Get', $Call, ['Message' => $Matches]);

            if ($Translation === null)
            {
                if (F::Environment() === 'Development')
                {
                    $Call['Replace'][$Call['Parsed']['Match'][$IX]] = '<span class="nl" lang="'.$Call['Locale'].'" source="parslet">' . array_shift($Matches) . '</span>';
                    echo $Call['Replace'][$Call['Parsed']['Match'][$IX]].PHP_EOL;
                }
                else
                    $Call['Replace'][$Call['Parsed']['Match'][$IX]] = '{'.array_shift($Matches).'}';
            }
            else
                if (is_scalar($Translation))
                    $Call['Replace'][$Call['Parsed']['Match'][$IX]] = $Translation;
                else
                    $Call['Replace'][$Call['Parsed']['Match'][$IX]] = '{}';
        }

        return $Call;
    });