<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Get', function ($Call)
    {
        $length = $Call['Length'];
        $password = '';

        $vowels = array('a', 'e', 'u');
        $cons = array('b', 'c', 'd', 'g', 'h', 'j', 'k','m', '_N', 'p', 'r', 's', 't', 'u', 'v', 'w', 'tr',
        'cr', 'br', 'fr', 'th', 'dr', 'ch', 'ph', 'wr', 'st', 'sp', 'sw', 'pr', 'sl', 'cl');

        $num_vowels = count($vowels);
        $num_cons = count($cons);

        for($i = 0; $i < $length; $i++)
            $password .= $cons[rand(0, $num_cons - 1)] . $vowels[rand(0, $num_vowels - 1)];

        return strtolower(substr($password, 0, $length));
    });
