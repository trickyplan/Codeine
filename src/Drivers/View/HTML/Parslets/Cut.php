<?php

    /* Codeine
     * @author BreathLess
     * @description Cut Parslet
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn ('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][2] as $IX => $Match)
        {
            $Root = simplexml_load_string('<root '.$Call['Parsed'][1][$IX].'></root>');

            $Inner = $Call['Parsed'][2][$IX];
            $Words = (int) $Root->attributes()->words;

            if (preg_match('/([^ \n\r]+[ \n\r]+){1,'.$Words.'}/s', $Inner, $Match))
                $Outer = $Match[0];
            else
                $Outer = $Inner;

            if (mb_strlen(trim($Inner)) >= mb_strlen($Outer))
            {
                $Hellip = isset($Root->attributes()->hellip) ? (string) $Root->attributes()->hellip: '&hellip;';
                $Hellip = isset($Root->attributes()->more)? '<a href="'.(string) $Root->attributes()->more.'">'.$Hellip.'</a>': $Hellip;

                $Outer.= $Hellip;
            }

            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);
        }

        return $Call;
    });