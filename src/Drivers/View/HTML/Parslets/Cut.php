<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Cut Parslet
     * @package Codeine
     * @version 8.x
     */

    setFn ('Parse', function ($Call)
    {
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            $Root = simplexml_load_string('<root '.$Call['Parsed']['Options'][$IX].'></root>');

            $Inner = $Call['Parsed']['Value'][$IX];

            if (F::Dot($Call ,'View.HTML.Parslet.Cut.StripTags.Enabled') or $Root->attributes()->strip)
                $Inner = strip_tags($Inner, F::Dot($Call ,'View.HTML.Parslet.Cut.StripTags.Allowed'));

            $Outer = $Inner;

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

            if ($Root->attributes()->hellip)
                $Hellip = (string) $Root->attributes()->hellip;
            else
                $Hellip = F::Dot($Call ,'View.HTML.Parslet.Cut.Hellip');

            if ($Root->attributes()->more)
                $Hellip = '<a href="'.((string) $Root->attributes()->more).'" class="hellip">'.$Hellip.'</a>';

            if (strlen($Outer) < strlen($Inner))
                $Outer.= strtr($Hellip, ['\n' => '<br/>']); // nl2br is unusable here

            $Call['Output'] = str_replace ($Call['Parsed']['Match'][$IX], $Outer, $Call['Output']);
        }

        return $Call;
    });