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
            $Outer = F::Run('Text.Beautifier', 'Do', $Call,
                [
                    'Value' => $Inner
                ]);
            $Call['Output'] = str_replace ($Call['Parsed']['Match'][$IX], $Outer, $Call['Output']);
        }

        return $Call;
    });