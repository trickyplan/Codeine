<?php

    /* Codeine
     * @author BreathLess
     * @description:  Piped sed command wrapper
     * @package Codeine
     * @version 6.0
     * @date 21.11.10
     * @time 2:40
     */

    $self::Fn('Exec', function ($Call)
    {
        return passthru($Call['Value'].' | sed -e "'.$Call['Pattern'].'"');
    });
