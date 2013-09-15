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
            $WordCount = (int) $Root->attributes()->words;

            $Words = preg_split('/[\s,]+/', $Inner);

            if (count($Words) > $WordCount)
            {
                $Outer = mb_substr($Inner, 0, mb_strpos($Inner, $Words[$WordCount-1]));
                $Hellip = isset($Root->attributes()->hellip) ? (string) $Root->attributes()->hellip: '&hellip;';
                $Hellip = isset($Root->attributes()->more)? '<a href="'.(string) $Root->attributes()->more.'">'.$Hellip.'</a>': $Hellip;

                $Outer.= $Hellip;
            }
            else
                $Outer = $Inner;

            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);
        }

        return $Call;
    });