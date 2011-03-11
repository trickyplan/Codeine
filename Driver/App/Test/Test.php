<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Test Controller
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 06.12.10
     * @time 13:38
     */

    self::Fn('Test', function ($Call)
    {
        $Call = Code::Run(
            array(
                'N' => 'System.Network.Speed',
                'F' => 'Test',
                'URL' => 'http://kernel.ubuntu.com/~kernel-ppa/mainline/v2.6.37.3-natty/BUILD.LOG')
        );

        var_dump($Call);
        die();
        return $Call;
    });
