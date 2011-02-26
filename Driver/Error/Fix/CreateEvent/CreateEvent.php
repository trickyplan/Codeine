<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: CreateEvent Fixer
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 26.02.11
     * @time 21:03
     */

    self::Fn('Catch', function ($Call)
    {
        $Configuration = json_decode(file_get_contents(Engine.'Config/Core/Code.json'), true);
        $Configuration['Hooks'][$Call['Data']['NewEvent']] = null;
        file_put_contents(Engine.'Config/Core/Code.json', json_encode($Configuration));
        
        return null;
    });
