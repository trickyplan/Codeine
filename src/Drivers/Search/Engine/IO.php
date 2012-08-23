<?php

    /* Codeine
     * @author BreathLess
     * @description Sphinx Driver 
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Query', function ($Call)
    {
        $SERP = F::Run('IO', 'Read', $Call['Engines'][$Call['Engine']],
            [
                'Fields' => ['ID'],
                'Scope' => $Call['Entity'],
                'Where' =>
                    [
                        'Index' => $Call['Query']
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