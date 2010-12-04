<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Strong
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 04.12.10
     * @time 15:23
     */

    self::Fn('Get', function ($Call)
    {
        $Password = '';
        $length = $Call['Length'];

        for ($a = 0; $a < $length; $a++)
            $Password.= chr(rand(32, 127));

        return $Password;
    });