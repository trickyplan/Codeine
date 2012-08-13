<?php

    /* Codeine
     * @author BreathLess
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Parse', function ($Call)
     {
        foreach ($Call['Parsed'][2] as $IX => $Match)
        {
            $Root = simplexml_load_string('<root '.$Call['Parsed'][1][$IX].'></root>');
            $Inner = (string) $Root;

            $Engine = isset($Root->attributes()->engine)? (string) $Root->attributes()->engine: 'Date';
            $Format = isset($Root->attributes()->format)? (string) $Root->attributes()->format: 'Y.m.d H:i:s';

            // TODO Due bug 13744 at w3c validator, time tag temporary diabled.
            // $Outer = '<time datetime="'.date(DATE_ISO8601, $Match).'">'.date($Format, $Inner).'</time>';

            $Outer = F::Run('Formats.Date.Engine.'.$Engine, 'Format',
                array(
                     'Format' => $Format,
                     'Value' => $Inner
                ));

            $Call['Locales']['Formats.Date:'.$Engine] = 'Formats.Date:'.$Engine;

            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);
        }

        return $Call;
     });