<?php

    /* Codeine
     * @author BreathLess
     * @description: SHA1 MHash Wrapper
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Get', function ($Call)
    {
        if (isset($Call['Key']))
            return mhash(MHASH_SHA1, $Call['Value'], $Call['Key']);
        else
            return mhash(MHASH_SHA1, $Call['Value']);
    });
