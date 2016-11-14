<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Block', function ($Call)
    {
        $Call['Run'] = '/error/403';
        return $Call;
    });