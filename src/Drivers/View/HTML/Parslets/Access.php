<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet 
     * @package Codeine
     * @version 8.x
     */

    setFn('Parse', function ($Call)
    {
        foreach ($Call['Parsed']['Match'] as $IX => $Match)
        {
            $Call['Run'] = [];

            unset($Call['Weight'], $Call['Decision']);

            $Root = simplexml_load_string('<access '.$Call['Parsed']['Options'][$IX].'></access>');

            $Attr = (array) $Root->attributes();

            foreach ($Attr['@attributes'] as $Key => $Value)
                $Call['Run'] = F::Dot($Call['Run'], $Key, $Value);

            if (F::Run('Security.Access', 'Check', $Call, $Call['Run']))
                $Outer = $Call['Parsed']['Value'][$IX];
            else
                $Outer = '';

            $Call['Output'] = str_replace ($Call['Parsed']['Match'][$IX], $Outer, $Call['Output']);
        }

        return $Call;
    });