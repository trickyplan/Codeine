<?php

    /* Codeine
     * @author BreathLess
     * @description: Random integer
     * @package Codeine
     * @version 7.x
     * @date 04.12.10
     * @time 14:56
     */

    self::setFn('Get', function ($Call)
    {
        $Alphabet = 'abcdefghijklmnopqrstuvxwyzABCDEFGHIJKLMNOPQRSTUVXYZ1234567890';
        $Output = '';

        $Min = 0;
        $Max = strlen($Alphabet);

        switch ($Call['Case'])
        {
            case 'Lower':
                $Max = strlen($Alphabet)/2;
            break;

            case 'Upper':
                $Min = strlen($Alphabet)/2;
            break;
        }

        for($IC = 0; $IC<$Call['Size']; $IC++)
            $Output.= $Alphabet[rand($Min, $Max)];

        return $Output;
    });
