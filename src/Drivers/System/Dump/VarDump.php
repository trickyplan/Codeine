<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: var_dump wrapper
     * @package Codeine
     * @version 8.x
     * @date 22.11.10
     * @time 5:21
     */

    setFn('Variable', function ($Call)
    {
        var_dump($Call['Value']);
    });
