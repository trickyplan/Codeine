<?php

    /* Codeine
     * @author BreathLess
     * @description: krumo wrapper
     * @package Codeine
     * @version 6.0
     * @date 22.11.10
     * @time 5:21
     */

    self::Fn('Variable', function ($Call)
    {
        // TODO Krumo Path
        krumo($Call['Value']);
    });
