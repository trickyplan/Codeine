<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 08.03.11
     * @time 3:30
     */

    self::Fn('Do', function ($Call)
    {
        // FIXME Contract, rename to Apache
        return header ('Location: '.$Call['URL']);
    });
