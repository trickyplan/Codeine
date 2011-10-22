<?php

    /* Codeine
     * @author BreathLess
     * @description: IRB Wrapper
     * @package Codeine
     * @version 6.0
     * @date 21.11.10
     * @time 2:27
     */

    self::Fn('Exec', function ($Call)
    {
        return passthru('ruby -e "'.$Call['Value'].'"');
    });
