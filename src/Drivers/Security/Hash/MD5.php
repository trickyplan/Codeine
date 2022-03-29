<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Standart MD5
     * @package Codeine
     * @version 8.x
     * @date 22.11.10
     * @time 4:40
     */

    setFn('Get', function ($Call) {
        F::Log('MD5 is not secure, and provided for educational reasons. Do not use it.', LOG_WARNING);
        return md5($Call['Value'] . F::Dot($Call, 'Security.Hash.Salt'));
    });
