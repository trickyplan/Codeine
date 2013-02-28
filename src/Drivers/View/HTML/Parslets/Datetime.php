<?php

    /* Codeine
     * @author BreathLess
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */

     setFn('Parse', function ($Call)
     {
        foreach ($Call['Parsed'][2] as $IX => $Match)
        {
            $Root = simplexml_load_string('<root '.$Call['Parsed'][1][$IX].'></root>');

            $Engine = isset($Root->attributes()->engine)? (string) $Root->attributes()->engine: 'Date';

            if (empty($Match)) $Match = time();
            // TODO Due bug 13744 at w3c validator, time tag temporary diabled.
            // $Outer = '<time datetime="'.date(DATE_ISO8601, $Match).'">'.date($Format, $Inner).'</time>';

            $Outer = [ 'Value' => $Match ];

            if (isset($Root->attributes()->format))
                $Outer['Format'] = (string) $Root->attributes()->format;

            $Outer = F::Run('Formats.Date.Engine.'.$Engine, 'Format', $Outer);

            $Call['Locales']['Formats.Date:'.$Engine] = 'Formats.Date:'.$Engine;

            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);
        }

        return $Call;
     });