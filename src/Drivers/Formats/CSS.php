<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Read', function ($Call)
    {
        $Data = [];
        if (mb_ereg_all('@(.*)\{(.*)\}@SsUu', $Call['Value'], $RuleSets))
            foreach($RuleSets[0] as $IX => $RuleSet)
            {
                $Data[trim($RuleSets[1][$IX])] = [];
                if (mb_ereg_all('@(.*)\:(.*);@SsUu', $RuleSets[2][$IX], $Rules))
                    foreach ($Rules[0] as $IC=> $Rule)
                        $Data[trim($RuleSets[1][$IX])][trim($Rules[1][$IC])] = trim($Rules[2][$IC]);
            }

         return $Data;
    });

    setFn('Write', function ($Call)
    {
        $Data = [];
        foreach($Call['Value'] as $Selector => $RuleSet)
        {
            $Rule = [];
            foreach ($RuleSet as $Key => $Value)
                $Rule[] = $Key.': '.$Value.';';

            $Data[] = $Selector.'{'.implode("\n", $Rule).'}';
        }
        
        return implode("\n", $Data);
    });