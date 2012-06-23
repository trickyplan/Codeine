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

            $Value = (string) $Root->attributes()->value;

            $Decision = false;

            if (null !== ($Eq = $Root->attributes()->eq))
                $Decision = ($Value == $Eq);

            if (null !== ($Neq = $Root->attributes()->neq))
                $Decision = ($Value != $Neq);

            if (null !== ($Lt = $Root->attributes()->lt))
                $Decision = ($Value < $Lt);

            if (null !== ($Gt = $Root->attributes()->gt))
                $Decision = ($Value == $Gt);


            if ($Decision)
                $Outer = $Call['Parsed'][2][$IX];

            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);
        }

        return $Call;
    });