<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (is_string($Call['Value']))
            $Call['Value'] = strip_tags($Call['Value']);

        return $Call;
    });