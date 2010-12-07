<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: YoutubeDL Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 08.12.10
     * @time 0:17
     */

    self::Fn('Read', function ($Call)
    {
        return passthru('yotube-dl '.$Call['Data']['Where']['ID'].' -o');
    });