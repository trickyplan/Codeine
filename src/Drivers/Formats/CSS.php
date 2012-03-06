<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Decode', function ($Call)
    {
        $Data = array();
        if (preg_match_all('@(.*)\{(.*)\}@SsUu', $Call['Value'], $RuleSets))
            foreach($RuleSets[0] as $IX => $RuleSet)
            {
                $Data[trim($RuleSets[1][$IX])] = array();
                if (preg_match_all('@(.*)\:(.*);@SsUu', $RuleSets[2][$IX], $Rules))
                    foreach ($Rules[0] as $IC=> $Rule)
                        $Data[trim($RuleSets[1][$IX])][trim($Rules[1][$IC])] = trim($Rules[2][$IC]);
            }

         return $Data;
    });

    self::setFn('Encode', function ($Call)
    {
        $Data = array();
        foreach($Call['Value'] as $Selector => $RuleSet)
        {
            $Rule = array();
            foreach ($RuleSet as $Key => $Value)
                $Rule[] = $Key.': '.$Value.';';

            $Data[] = $Selector.'{'.implode("\n", $Rule).'}';
        }
        
        return implode("\n", $Data);
    });