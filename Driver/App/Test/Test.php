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
                'N' => 'System.Network.Ping',
                'Count' => 3,
                'F' => 'Times',
                'Host' => 'ya.ru')
        );

        var_dump($Call);
        die();
        return $Call;
    });
