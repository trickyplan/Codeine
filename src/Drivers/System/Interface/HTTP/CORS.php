<?php

    setFn('Do', function ($Call) {

        $Call['HTTP']['Headers']['Access-Control-Allow-Origin:'] = F::Dot($Call, 'CORS.Origin');

        if (F::Dot($Call, 'HTTP.Method') == 'OPTIONS') 
        {
            $AllowedHeaders = array_merge(
                F::Dot($Call, 'HTTP.Request.Headers.Access-Control-Request-Headers'),
                F::Dot($Call, 'CORS.Headers')
            );

            $Call['HTTP']['Headers']['Access-Control-Allow-Methods:'] = implode(', ', F::Dot($Call, 'CORS.Methods'));
            $Call['HTTP']['Headers']['Access-Control-Allow-Headers:'] = implode(', ', $AllowedHeaders);
            $Call['HTTP']['Headers']['Access-Control-Max-Age:'] = F::Dot($Call, 'CORS.Max-Age');
            $Call['HTTP']['Headers']['Access-Control-Allow-Credentials'] = F::Dot($Call, 'CORS.Credentials');
        } 

        return $Call;
    });