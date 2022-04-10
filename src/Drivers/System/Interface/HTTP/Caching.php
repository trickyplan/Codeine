<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 2019.x
     */

    setFn('Do', function ($Call) {
        if (F::Dot($Call, 'HTTP.Caching.Enabled')) {
            if (F::Dot($Call, 'HTTP.Caching.Private')) {
                $Directives[] = 'private';
            } else {
                $Directives[] = 'public';
            }

            if (null === ($TTL = F::Dot($Call, 'HTTP.Caching.TTL'))) {
            } else {
                $Directives[] = 'max-age=' . $TTL;
            }

            $Call = F::Dot($Call, 'HTTP.Headers.Cache-Control:', implode(', ', $Directives));
        }
        return $Call;
    });
