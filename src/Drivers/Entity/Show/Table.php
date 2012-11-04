<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Element = F::Run('Entity', 'Read', $Call)[0];

        $Data = [];
        F::Map($Element, function ($Key, $Value, $D, $Fullkey) use ($Call, &$Data)
        {
            if (!is_array($Value))
                $Data[] = ['<l>'.$Call['Entity'].'.Entity:'.substr($Fullkey, 1).'</l>', $Value];
        });

        $Call['Output']['Content'][]
        =
            [
                'Type' => 'Table',
                'Value' => $Data
            ];

        return $Call;
    });