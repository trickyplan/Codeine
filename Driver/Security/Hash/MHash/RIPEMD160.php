<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: RIPEMD160 MHash Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     */

    
    self::Fn('Get', function ($Call)
    {
        if (isset($Call['Key']))
            return mhash(MHASH_RIPEMD160, $Call['Input'], $Call['Key']);
        else
            return mhash(MHASH_RIPEMD160, $Call['Input']);
    });
