<?php

    setFn('Do', function ($Call) {

        $Call['HTTP']['Headers']['Access-Control-Allow-Credentials:'] = F::Dot($Call, 'CORS.Credentials');

        if (F::Dot($Call, 'HTTP.Method') == 'OPTIONS') 
            ;
        else {
            if ($Origin = F::Dot($Call, 'HTTP.Request.Headers.Origin')) {
                $Call['HTTP']['Headers']['Access-Control-Allow-Origin:'] = $Origin;
            }
        }

        return $Call;
    });