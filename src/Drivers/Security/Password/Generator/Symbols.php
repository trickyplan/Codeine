<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Password generator
     * @package Codeine
     * @version 8.x
     * @date 04.12.10
     * @time 14:56
     */

    setFn('Get', function ($Call)
    {
        $Output = '';

        $Chars = str_split($Call['Password']['Alphabet'][$Call['Mode']], 1);

        for($IC = 0; $IC<$Call['Size']; $IC++)
            $Output.= $Chars[array_rand($Chars)];

        if (isset($Call['Case']))
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
