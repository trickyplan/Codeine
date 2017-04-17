<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */

     setFn('Parse', function ($Call)
     {
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            $Root = simplexml_load_string('<root '.$Call['Parsed']['Options'][$IX].'></root>');
            $Language = isset($Root->attributes()->lang)? (string) $Root->attributes()->lang: 'ru';
            $Case = isset($Root->attributes()->case)? (string) $Root->attributes()->case: 'Genitivus';

            if (empty($Match))
                $Outer = '';
            else
                $Outer = F::Run('Text.Morphology.Case', 'Convert',
                        [
                            'Value' => $Match,
                            'Case'  => $Case,
                            'Language' => $Language
                        ]);

            $Call['Output'] = str_replace ($Call['Parsed']['Match'][$IX], $Outer, $Call['Output']);
        }

        return $Replaces;
     });