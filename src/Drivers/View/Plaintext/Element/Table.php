<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Make', function ($Call)
    {
        $Output = '';

        foreach ($Call['Value'] as $Row)
        {
            foreach ($Row as $Value)
                 $Output .= $Value."\t";

            $Output.= "\n";
        }

        return $Output."\n";
    });