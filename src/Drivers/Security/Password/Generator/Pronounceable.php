<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Get', function ($Call)
    {
        $Password = '';

        $NumVowels = count($Call['Pronounceable']['Vowels']);
        $NumCons = count($Call['Pronounceable']['Cons']);

        for($IX = 0; $IX < $Call['Length']; $IX++)
            $Password .= $Call['Pronounceable']['Cons'][rand(0, $NumCons - 1)] . $Call['Pronounceable']['Vowels'][rand(0, $NumVowels - 1)];

        return strtolower($Password);
    });
