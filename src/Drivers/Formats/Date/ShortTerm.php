<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Date() engine 
     * @package Codeine
     * @version 8.x
     */

    setFn('Format', function ($Call)
    {
        $Output = [];

        if (isset($Call['Value']))
        {
            foreach ($Call['Period']['Units'] as $Period => $Value)
                if ($Call['Value'] >= $Value)
                {
                    $Units = floor($Call['Value']/$Value);
                    $Output[] = $Units.' <l>Formats.Period:'.$Period.'.'.($Units%20).'</l>';
                    $Call['Value'] -= $Units*$Value;
                }

            if (empty($Output))
                $Result = '0 <l>Formats.Period:Seconds.10</l>';
            else
                $Result = implode(' ', array_slice($Output,0,$Call['Period']['Format']));
        }
        else
            $Result = null;

        return $Result;
    });