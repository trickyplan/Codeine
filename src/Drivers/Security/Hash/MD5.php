<?php

    /* Codeine
     * @author BreathLess
     * @description: Standart MD5
     * @package Codeine
     * @version 7.2
     * @date 22.11.10
     * @time 4:40
     */

    self::setFn('Get', function ($Call)
    {
        if(isset($Call['Key']))
            $Call['Value'] .= $Call['Key'];

        return md5($Call['Value']);
    });
