<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Feed Runner
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 24.11.10
     * @time 1:26
     */

    self::Fn('Run', function ($Call)
    {
        $Output = array();
        foreach ($Call['Call']['Calls'] as $IX => $OneCall)
            $Output[$IX] = Code::Run(
                                Code::ConfWalk($OneCall, array('Data'=>$Call['Call']['Data'])),
                                    $Call['Mode']);

        return $Output;
    });