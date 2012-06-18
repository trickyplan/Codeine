<?php

    /* Codeine
     * @author BreathLess
     * @description Layout Parslet 
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][2] as $IX => $Match)
        {
            $Root = simplexml_load_string('<root '.$Call['Parsed'][1][$IX].'></root>');

            $Outer = '';

            $Value = (int) $Root->attributes()->value;

            $Decision = false;

            if (null !== ($Eq = $Root->attributes()->eq) && $Value == $Eq)
                $Decision = true;

            if (null !== ($Lt = $Root->attributes()->lt) && $Value < $Lt)
                $Decision = true;

            if (null !== ($Gt = $Root->attributes()->gt) && $Value > $Gt)
                $Decision = true;

            if ($Decision)
                $Outer = $Call['Parsed'][2][$IX];

            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);
        }

        return $Call;
    });