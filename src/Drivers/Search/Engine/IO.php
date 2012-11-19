<?php

    /* Codeine
     * @author BreathLess
     * @description Sphinx Driver 
     * @package Codeine
     * @version 7.x
     */

    setFn('Query', function ($Call)
    {

        $SERP = F::Run('IO', 'Read', $Call, $Call['Engines'][$Call['Engine']],
            [
                'Fields' => ['ID'],
                'Scope' => $Call['Entity'],
                'Where' =>
                    [
                        'Keywords' => F::Run('Text.Transform.Transliterate.Passport', '2English', ['Value' => F::Run('Text.Index.Metaphone.Russian', 'Get',
            ['Value' => mb_strtolower($Call['Query'])])]) // FIXME SOON
                    ]
            ]);

        if (empty($SERP))
            $Result = null;
        else
        {
            $Result = [];

            foreach ($SERP as $cSERP)
                $Result[$cSERP['ID']] = 100; // TODO Relevancy
        }

        return $Result;
     });