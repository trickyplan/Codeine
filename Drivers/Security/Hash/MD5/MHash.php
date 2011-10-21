<?php

    /* Codeine
     * @author BreathLess
     * @description: MD5 MHash Wrapper
     * @package Codeine
     * @version 6.0
     */

    
    self::Fn('Get', function ($Call)
    {
        if (isset($Call['Key']))
            return mhash(MHASH_MD5, $Call['Value'], $Call['Key']);
        else
            return mhash(MHASH_MD5, $Call['Value']);
    });
