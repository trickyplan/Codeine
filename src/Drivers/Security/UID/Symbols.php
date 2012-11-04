<?php

    /* Codeine
     * @author BreathLess
     * @description: Random integer
     * @package Codeine
     * @version 7.x
     * @date 04.12.10
     * @time 14:56
     */

    setFn('Get', function ($Call)
    {
        $Output = '';

        $SZ = strlen($Call['UID']['Alphabet']);

        for($IC = 0; $IC<$Call['Size']; $IC++)
            $Output.= $Call['UID']['Alphabet'][rand(0, $SZ)];

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
