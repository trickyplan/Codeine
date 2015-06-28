<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */

     setFn('Parse', function ($Call)
     {
        foreach ($Call['Parsed'][2] as $IX => $Match)
        {
            $Root = simplexml_load_string('<root '.$Call['Parsed'][1][$IX].'></root>');
            $Language = isset($Root->attributes()->lang)? (string) $Root->attributes()->lang: 'ru';
            $Case = isset($Root->attributes()->case)? (string) $Root->attributes()->case: 'Genitivus';

            if ((mb_substr($Match, 0, 1) !== '"') && (mb_substr($Match, -1, 1) !== '"'))
                $Outer = F::Run('Text.Morphology.Case', 'Convert',
                    [
                        'Value' => $Match,
                        'Case'  => $Case,
                        'Language' => $Language
                    ]);
            else
                $Outer = $Match;

            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);
        }

        return $Call;
     });