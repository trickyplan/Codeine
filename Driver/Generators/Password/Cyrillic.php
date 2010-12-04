<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Cyrillic letters
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

        $String = 'АБВГДЕЖЗИКЛМНОПРСТУФХЧШЫЭЮЯабвгдеёжзийклмнопрстуфхцчшщъэюя';
        for ($a = 0; $a < $length; $a++)
            $Password.= mb_substr($String, mt_rand(0,66),1);

        return $Password;
    });