<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Get', function ($Call)
    {
        $length = $Call['Length'];
        $password = '';

        $vowels = ['a', 'e', 'u'];
        $cons = ['b', 'c', 'd', 'g', 'h', 'j', 'k','m', 'N', 'p', 'r', 's', 't', 'u', 'v', 'w', 'tr',
        'cr', 'br', 'fr', 'th', 'dr', 'ch', 'ph', 'wr', 'st', 'sp', 'sw', 'pr', 'sl', 'cl'];

        $num_vowels = count($vowels);
        $num_cons = count($cons);

        for($i = 0; $i < $length; $i++)
            $password .= $cons[rand(0, $num_cons - 1)] . $vowels[rand(0, $num_vowels - 1)];

        return strtolower(substr($password, 0, $length));
    });
