<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Filter', function ($Call)
    {
        // Determine Language
        // Load Stop words list
        $Call['Stop Words'] = F::loadOptions('Text.Stopwords.ru')['Stop Words'];
        $Call['Words'] = array_filter($Call['Words'],
            function ($Word) use ($Call)
            {
                return !in_array($Word, $Call['Stop Words']);
            }
        );
        return $Call;
    });