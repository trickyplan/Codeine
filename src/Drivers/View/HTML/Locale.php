<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: 
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        $Call['Parsed'] = F::Run('Text.Regex', 'All',
        [
            'Pattern' => $Call['Locale Pattern'],
            'Value'   => $Call['Output']
        ]);

        if ($Call['Parsed'])
        {
            $Locales = [];

            foreach ($Call['Parsed'][1] as &$Match)
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
                        $Match = '<span class="nl" lang="'.$Call['Locale'].'">' . array_shift($Matches) . '</span>';
                        echo $Match.PHP_EOL;
                    }
                    else
                        $Match = '';
                }
                else
                    if (is_scalar($Translation))
                        $Match = $Translation;
                    else
                        $Match = '{}';
            }
            
            $Call['Output'] = str_replace($Call['Parsed'][0], $Call['Parsed'][1], $Call['Output']);
        }

        return $Call;
    });
