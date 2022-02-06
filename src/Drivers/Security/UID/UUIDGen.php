<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: GUID
     * @package Codeine
     * @version 8.x
     * @date 04.12.10
     * @time 14:56
     */

    setFn('Get', function ($Call)
    {
        return shell_exec('uuidgen');
    });
