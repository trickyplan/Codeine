<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 25.02.11
     * @time 22:19
     */

    self::Fn('Catch', function ($Call)
    {
        header ('Location: http://oxme.ru/projects/codeine/wiki/'.str_replace('.', '', $Call['Data']['Event']));
        return ;
    });
