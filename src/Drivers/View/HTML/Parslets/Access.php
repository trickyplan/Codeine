<?php

    /* Codeine
     * @author BreathLess
     * @description Exec Parslet 
     * @package Codeine
     * @version 7.x
     */

    setFn('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][0] as $IX => $Match)
        {
            $Call['Run'] = [];

            unset($Call['Weight'], $Call['Decision']);

            $Root = simplexml_load_string('<access '.$Call['Parsed'][1][$IX].'></access>');

            $Attr = (array) $Root->attributes();

            foreach ($Attr['@attributes'] as $Key => $Value)
                $Call['Run'] = F::Dot($Call['Run'], $Key, $Value);

            $Call = F::Apply('Security.Access', 'Check', $Call, $Call['Run']);

            if ($Call['Decision'] === true)
                $Outer = $Call['Parsed'][2][$IX];
            else
                $Outer = '';

            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);
        }

        return $Call;
    });