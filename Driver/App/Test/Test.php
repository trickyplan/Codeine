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
                'N' => 'Code.Flow.Orchestra',
                'F' => 'Run',
                'Steps' => json_decode(file_get_contents(Engine.'Config/Scripts/Test.json'),true)
            )
        );

        var_dump($Call);
        die();
        return $Call;
    });
