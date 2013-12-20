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
            $Root = simplexml_load_string('<currency '.$Call['Parsed'][1][$IX].'></currency>');

            if (isset($Root->attributes()->from))
            {
                $Call['From'] = (string) $Root->attributes()->from;

                if (isset($Root->attributes()->to))
                    $Call['Currency']['To'] = (string) $Root->attributes()->to;

                if (isset($Root->attributes()->precision))
                    $Call['Currency']['Precision'] = (string) $Root->attributes()->precision;

                if (is_numeric($Match))
                {
                    $Outer = number_format(F::Run('Finance.Currency', 'Convert',
                            [
                                'From' => $Call['From'],
                                'To' => $Call['Currency']['To'],
                                'Value' => $Match
                            ]), $Call['Currency']['Precision'], ',', ' ').'&nbsp;'.$Call['Currency']['To'];
                }
            }

            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);
        }

        return $Call;
     });