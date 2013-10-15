<?php

    /* Codeine
     * @author BreathLess
     * @description Cut Parslet
     * @package Codeine
     * @version 7.x
     */

    setFn ('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][2] as $IX => $Match)
        {
            $Root = simplexml_load_string('<root '.$Call['Parsed'][1][$IX].'></root>');

            $Inner = $Call['Parsed'][2][$IX];

            if ($Root->attributes()->chars)
                $Outer = F::Run('Text.Cut', 'Do',
                    [
                        'Cut'   => 'Chars',
                        'Value' => $Inner,
                        'Chars' => (int) $Root->attributes()->chars
                    ]);

            if ($Root->attributes()->words)
                $Outer = F::Run('Text.Cut', 'Do',
                    [
                        'Cut'   => 'Words',
                        'Value' => $Inner,
                        'Words' => (int) $Root->attributes()->words
                    ]);

            if ($Root->attributes()->sentences)
                $Outer = F::Run('Text.Cut', 'Do',
                    [
                        'Cut'   => 'Sentences',
                        'Value' => $Inner,
                        'Sentences' => (int) $Root->attributes()->sentences
                    ]);

            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);
        }

        return $Call;
    });