<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Standart SHA1
     * @package Codeine
     * @version 8.x
     * @date 22.11.10
     * @time 4:41
     */

    setFn('Get', function ($Call) {
        return sha1($Call['Value'] . F::Dot($Call, 'Security.Hash.Salt'));
    });
