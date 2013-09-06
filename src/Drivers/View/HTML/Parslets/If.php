<?php

    /* Codeine
     * @author BreathLess
     * @description Layout Parslet 
     * @package Codeine
     * @version 7.x
     */

    setFn ('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][2] as $IX => $Match)
        {
            $Root = simplexml_load_string('<if '.$Call['Parsed'][1][$IX].'></if>');

            $Outer = '';

            if ($Root !== false)
                {
                    $Value = (string) $Root->attributes()->value;

                    $Decision = false;

                    if (null != ($Eq = (string) $Root->attributes()->eq))
                    {
                        $Decision = ($Value == $Eq);
                    }

                    if (null != ($Neq = (string) $Root->attributes()->neq))
                        $Decision = ($Value != $Neq);

                    if (null != ($Lt = $Root->attributes()->lt))
                        $Decision = ((float)$Value < (float) $Lt);

                    if (null != ($Gt = $Root->attributes()->gt))
                        $Decision = ((float) $Value > (float) $Gt);

                    if ($Decision)
                        $Outer = $Call['Parsed'][2][$IX];
                }

            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);
        }

        return $Call;
    });