<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description:  Piped sed command wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 21.11.10
     * @time 2:40
     */

    $self::Fn('Exec', function ($Call)
    {
        return passthru($Call['Input'].' | sed -e "'.$Call['Pattern'].'"');
    });