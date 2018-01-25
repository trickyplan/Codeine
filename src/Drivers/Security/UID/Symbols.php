<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Random integer
     * @package Codeine
     * @version 8.x
     * @date 04.12.10
     * @time 14:56
     */

    setFn('Get', function ($Call)
    {
        $Output = '';

        $SZ = strlen($Call['UID']['Alphabet']);
        $Call['UID']['Alphabet'] = str_split($Call['UID']['Alphabet'], 1);
        for($IC = 0; $IC < $Call['UID']['Size']; $IC++)
            $Output.= $Call['UID']['Alphabet'][rand(0, $SZ)];

        switch ($Call['UID']['Case'])
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
