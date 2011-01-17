<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Dumper Executor
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 3:05
     */

    self::Fn('Run', function ($Call)
    {
        var_dump(Code::Run($Call['Call']));
    });