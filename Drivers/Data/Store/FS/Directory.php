<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 13.08.11
     * @time 22:37
     */

    self::Fn('Open', function ($Call)
    {
        return true;
    });

    self::Fn('Read', function ($Call)
    {
        return file_get_contents($Call['URL'].'/'.$Call['Scope'].'/'.$Call['ID']);
    });
