<?php

    /* Codeine
     * @author BreathLess
     * @description: whois command wrapper
     * @package Codeine
     * @version 6.0
     * @date 08.12.10
     * @time 0:27
     */

    self::Fn('Exec', function ($Call)
    {
        return shell_exec ('whois '.$Call['Value']);
    });
