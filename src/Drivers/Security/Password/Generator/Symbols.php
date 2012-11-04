<?php

    /* Codeine
     * @author BreathLess
     * @description: Password generator
     * @package Codeine
     * @version 7.x
     * @date 04.12.10
     * @time 14:56
     */

    setFn('Get', function ($Call)
    {
        $Output = '';

        for($IC = 0; $IC<$Call['Size']; $IC++)
            $Output.= array_rand($Call['Password']['Alphabet'][$Call['Mode']]);

        switch ($Call['Case'])
        {
            case 'Lower':
                $Output = strtolower($Output);
            break;

            case 'Upper':
                $Output = strtoupper($Output);
            break;
        }

        return $Output;
    });
