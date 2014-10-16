<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Make', function ($Call)
    {
        foreach ($Call['Attributes']['String'] as $Attribute => $Value)
        {
             if (isset($Call[$Attribute]))
                 $Attributes[] = strtolower($Attribute).'="'.$Call[$Attribute].'"';
             else
                 if (!empty($Value))
                     $Attributes[] = strtolower($Attribute).'="'.$Value.'"';
        }

        foreach ($Call['Attributes']['Boolean'] as $Attribute => $Value)
        {
             if (isset($Call[$Attribute]))
                 $Attributes[] = strtolower($Attribute);
             else
                 if (!empty($Value) && $Value)
                     $Attributes[] = strtolower($Attribute);
        }

        if (isset($Call['Block']) && $Call['Block'])
            $Call['HTML'] = '<'.$Call['Tag'].' '.implode(' ', $Attributes).'>'.$Call['Value'].'</'.$Call['Tag'].'>';
        else
            $Call['HTML'] = '<'.$Call['Tag'].' '.implode(' ', $Attributes).' />';

        return $Call;
    });