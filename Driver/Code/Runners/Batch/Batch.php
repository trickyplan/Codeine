<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Batch Runner
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 24.11.10
     * @time 0:58
     */

    self::Fn('Run', function ($Call)
    {
        $Output = array();
        $Call['Prototype']['Batch'] = $Call['Sets'];

        foreach ($Call['Sets'] as $IX => $Set)
            $Output[$IX] = self::Run(
                                self::ConfWalk($Call['Prototype'], $Set),
                                    $Call['Mode']);

        return $Output;
    });