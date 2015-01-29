<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Standart MD5
     * @package Codeine
     * @version 8.x
     * @date 22.11.10
     * @time 4:40
     */

    setFn('Get', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Live($Call['Modes'][$Call['Mode']], $Call);
        else
            return null;
    });
