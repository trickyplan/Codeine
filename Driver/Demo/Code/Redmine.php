<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 31.03.11
     * @time 1:15
     */

    self::Fn('Do', function ($Call)
    {
        $Result = Code::Run(
                array(
                    'N' => 'Hooks.Log.Redmine',
                    'F' => 'Catch'));

        var_dump($Result);
        return ;
    });
