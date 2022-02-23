<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Date() engine 
     * @package Codeine
     * @version 8.x
     */

    setFn('Format', function ($Call)
    {
        if (isset($Call['Value']) && is_numeric($Call['Value']))
        {
            $Time = time();

            if ($Time < $Call['Value'])
            {
                $Prefix = '<codeine-locale>(.*)</codeine-locale> ';
                $Postfix = ' <codeine-locale>(.*)</codeine-locale>';
            }
            elseif ($Time > $Call['Value'])
            {
                $Prefix = '<codeine-locale>(.*)</codeine-locale> ';
                $Postfix = ' <codeine-locale>(.*)</codeine-locale>';
            }
            else
                return '<codeine-locale>(.*)</codeine-locale>';

            $Call['Value'] = abs($Time-$Call['Value']);

            $Output = [];

            foreach ($Call['Period']['Units'] as $Period => $Value)
                if ($Call['Value'] >= $Value)
                {
                    $Units = floor($Call['Value'] / $Value);
                    $Output[] = $Units.' <codeine-locale>(.*)</codeine-locale>';
                    $Call['Value'] -= $Units*$Value;
                }

            return $Prefix.implode(' ', array_slice($Output,0,$Call['Period']['Format'])).$Postfix;
        }
        else
             return null;
    });