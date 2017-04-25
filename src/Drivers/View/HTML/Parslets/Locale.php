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
                    $Replaces[$IX] = '<span class="nl" lang="'.$Call['Locale'].'">' . array_shift($Matches) . '</span>';
                    echo $Replaces[$IX].PHP_EOL;
                }
                else
                    $Replaces[$IX] = '';
            }
            else
                if (is_scalar($Translation))
                    $Replaces[$IX] = $Translation;
                else
                    $Replaces[$IX] = '{}';
        }

        return $Replaces;
    });