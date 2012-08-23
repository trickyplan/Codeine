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
        $LowW = 'abcdefghijklmnopqrstuvxwyz';
        $HighW = 'ABCDEFGHIJKLMNOPQRSTUVXYZ';

        $Output = '';

        foreach ($Call['Mask'] as $IX => $Value)
            if ($Value == '\\')
            {
                switch($Call['Mask'][$IX+1])
                {
                    case 'd':
                        $Output.= rand(0,9);
                    break;

                    case 'w':
                        $Output.= $LowW[rand(0,26)];
                    break;

                    case 'W':
                        $Output.= $HighW[rand(0,26)];
                    break;
                }

                next($Call['Mask']);
            }
            else
                $Output.= $Value;

        return $Output;
    });
