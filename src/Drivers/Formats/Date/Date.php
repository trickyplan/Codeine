<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Date() engine 
     * @package Codeine
     * @version 8.x
     */

    setFn('Format', function ($Call)
    {
        if (isset($Call['Value']) && !empty($Call['Value']))
        {
            if ($Call['Format'] == 'ISO8601')
                $Call['Format'] = DATE_ISO8601;

            return date($Call['Format'], (int) $Call['Value']);
        }
        else
            return date($Call['Format']);
    });