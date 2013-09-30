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

        if (!empty($Call['Output']['Content']))
        {
            foreach ($Call['Output']['Content'] as $Key => $Value)
                $Rows[] = ['id' => $Value['ID'], 'text' => F::Dot($Value, $Call['Key'])];

            $Call['Output']['Content'] = $Rows;
        }

        return $Call;
    });