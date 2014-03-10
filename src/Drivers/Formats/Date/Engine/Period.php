<?php

    /* Codeine
     * @author BreathLess
     * @description Date() engine 
     * @package Codeine
     * @version 7.x
     */

    setFn('Format', function ($Call)
    {
        if (isset($Call['Value']))
        {
            $Time = time();

            if ($Time < $Call['Value'])
            {
                $Prefix = '<l>Formats.Period:Future.Prefix</l> ';
                $Postfix = ' <l>Formats.Period:Future.Postfix</l>';
            }
            elseif ($Time > $Call['Value'])
            {
                $Prefix = '<l>Formats.Period:Past.Prefix</l> ';
                $Postfix = ' <l>Formats.Period:Past.Postfix</l>';
            }
            else
                return '<l>Formats.Period:Now</l>';

            $Call['Value'] = abs($Time-$Call['Value']);

            $Output = [];
            foreach ($Call['Period']['Units'] as $Period => $Value)
                if ($Call['Value'] >= $Value)
                {
                    $Units = floor($Call['Value']/$Value);
                    $Output[] = $Units.' <l>Formats.Period:'.$Period.'.'.($Units%20).'</l>';
                    $Call['Value'] -= $Units*$Value;
                }

            return $Prefix.implode(' ', array_slice($Output,0,$Call['Period']['Format'])).$Postfix;
        }
    });