<?php

    /* Codeine
     * @author BreathLess
     * @description Date() engine 
     * @package Codeine
     * @version 7.x
     */

    setFn('Format', function ($Call)
    {
        $Diff = time()- $Call['Value'];

        if ($Diff < $Call['Human Date']['Threshold']['Now'])
            $Call['Value'] = '<l>Formats.Date.Human.Words:Now</l>';

        if (($Diff < $Call['Human Date']['Threshold']['Second']) and ($Diff > $Call['Human Date']['Threshold']['Now']))
        {
            $Call['Value'] = $Diff.' <l>Formats.Date.Human.Words:Second.'.($Diff%10).'</l>';
        }

        if (($Diff < $Call['Human Date']['Threshold']['Minute']) and ($Diff > $Call['Human Date']['Threshold']['Second']))
        {
            $Minutes = round($Diff/60);
            $Call['Value'] = $Minutes.' <l>Formats.Date.Human.Words:Minute.'.($Minutes%10).'</l>';
        }

        if (($Diff < $Call['Human Date']['Threshold']['Hour']) and ($Diff > $Call['Human Date']['Threshold']['Minute']))
        {
            $Hours = round($Diff/3600);
            $Call['Value'] = $Hours.' <l>Formats.Date.Human.Words:Hour.'.($Hours%10).'</l>';
        }

        if (($Diff > $Call['Human Date']['Threshold']['Day']) and ($Diff < $Call['Human Date']['Threshold']['Week']))
        {
            $Hours = round($Diff/3600);
            $Call['Value'] = $Hours.' <l>Formats.Date.Human.Words:Day.'.($Hours%10).'</l>';
        }

        return $Call['Value'];
     });