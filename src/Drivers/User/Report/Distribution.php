<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read',
                    [
                         'Entity' => 'User',
                         'Keys' => [$Call['ID']]
                    ]);

        $Elements = F::Extract($Elements, [$Call['Action']]);

        $Elements = array_count_values(array_map(function($Element) {return trim($Element);},$Elements[$Call['ID']]));

        $Data = [['Значение', 'Количество']];
        foreach ($Elements as $Key => $Value)
            $Data[] = [(string) $Key, $Value];

        $Call['Output'] = $Data;

        return $Call;
    });