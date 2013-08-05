<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Get', function ($Call)
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            preg_match_all ('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $Parsed);

            $Languages = array_combine ($Parsed[1], $Parsed[4]);

            foreach ($Languages as $Language => $Q)
                if ($Q === '') $Languages[$Language] = 1;

            arsort ($Languages, SORT_NUMERIC);

            foreach ($Languages as $Language => $Quality)
            {
                if (isset($Call['Languages']['Map'][$Language]))
                    return $Call['Languages']['Map'][$Language];
            }
        }

        return $Call['Languages']['Default'];
    });