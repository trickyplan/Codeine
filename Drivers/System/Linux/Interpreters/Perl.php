<?php

    /* Codeine
     * @author BreathLess
     * @description: Perl Wrapper
     * @package Codeine
     * @version 6.0
     * @date 21.11.10
     * @time 2:27
     */

    self::Fn('Exec', function ($Call)
    {
        return passthru('perl -e \''.$Call['Value'].'\'');
    });
