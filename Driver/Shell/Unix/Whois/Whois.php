<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: whois command wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 08.12.10
     * @time 0:27
     */

    self::Fn('Exec', function ($Call)
    {
        return passtrhu ('whois '.$Call['Input']);
    });