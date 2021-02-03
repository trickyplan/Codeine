<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Layout Parslet
     * @package Codeine
     * @version 8.x
     */

    setFn ('Parse', function ($Call)
    {
        $Replaces = [];

        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            if (strpos($Match, ',') !== false)
                $Matches = explode(',', $Match);
            else
                $Matches = [$Match];
                
             $Translation = F::Run('Locale', 'Get', $Call, ['Message' => $Matches]);

            if ($Translation === null)
            {
                if (F::Environment() === 'Development')
                {
                    $Replaces[$Call['Parsed']['Match'][$IX]] = '<span class="nl" lang="'.$Call['Locale'].'" source="parslet">' . array_shift($Matches) . '</span>';
                    echo $Replaces[$Call['Parsed']['Match'][$IX]].PHP_EOL;
                }
                else
                    $Replaces[$Call['Parsed']['Match'][$IX]] = '{'.array_shift($Matches).'}';
            }
            else
                if (is_scalar($Translation))
                    $Replaces[$Call['Parsed']['Match'][$IX]] = $Translation;
                else
                    $Replaces[$Call['Parsed']['Match'][$IX]] = '{}';
        }

        return $Replaces;
    });