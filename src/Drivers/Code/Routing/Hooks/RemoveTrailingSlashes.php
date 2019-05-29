<?php

/* Codeine
 * @author bergstein@trickyplan.com
 * @description  
 * @package Codeine
 * @version 2019.x
 */

    setFn('beforeRoute', function ($Call)
    {
        if ($Call['Run'] == '/')
            ;
        else
            if (preg_match('@(/+)$@', $Call['Run']))
            {
                F::Log('Trailing slash removed', LOG_INFO);
                $Call['Run'] = preg_replace('@(/+)$@', '', $Call['Run']);
            }

        return $Call;
    });