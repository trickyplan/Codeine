<?php

    /* Codeine
     * @author BreathLess
     * @description: Latin letters
     * @package Codeine
     * @version 6.0
     * @date 04.12.10
     * @time 15:23
     */

    self::Fn('Get', function ($Call)
    {
        $Password = '';
        $length = $Call['Length'];

        for ($a = 0; $a < $length; $a++)
            $Password.= chr(rand(65, 90));

        return $Password;
    });
