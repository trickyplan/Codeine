<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Output']['Content'] = F::Run ('Entity', 'Read', $Call,
                    [
                        'Entity' => $Call['Entity']
                    ]);

        foreach ($Call['Output']['Content'] as $Key => $Value)
            $Rows[] = ['id' => $Key, 'text' => F::Dot($Value, $Call['Key'])];

        $Call['Output']['Content'] = $Rows;

        return $Call;
    });