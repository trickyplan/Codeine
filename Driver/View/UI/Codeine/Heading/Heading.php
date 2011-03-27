<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: H1, H2, H3
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 04.12.10
     * @time 18:29
     */

    self::Fn('Make', function ($Call)
    {
        return '<h'.$Call['Level'].'>'.$Call['Data'].'</h'.$Call['Level'].'>';
    });
