<?php

    setFn('Do', function ($Call) {
        if (F::Dot($Call, 'HTTP.CORS.Enabled')) {
            $Call['HTTP']['Headers']['Access-Control-Allow-Credentials:'] = F::Dot($Call, 'HTTP.CORS.Credentials');
            $Call['HTTP']['Headers']['Access-Control-Allow-Headers: '] = 'X-Requested-With,Content-Type';
            $Call['HTTP']['Headers']['Access-Control-Allow-Methods: '] = implode(
                ',',
                F::Dot($Call, 'HTTP.Methods.Allowed') ?? []
            );

            if ($Call['HTTP']['Method'] == 'OPTIONS') {
                $Call = F::Dot($Call, 'HTTP.Caching.Enabled', F::Dot($Call, 'HTTP.CORS.Caching.Enabled'));
                $Call = F::Dot($Call, 'HTTP.Caching.Private', F::Dot($Call, 'HTTP.CORS.Caching.Private'));
                $Call = F::Dot($Call, 'HTTP.Caching.TTL', F::Dot($Call, 'HTTP.CORS.Caching.TTL'));
            }

            $Origin = F::Dot($Call, 'HTTP.Request.Headers.Origin');

            if (empty($Origin)) {
                $Origin = (isset($_SERVER['HTTP_ORIGIN'])) ? $_SERVER['HTTP_ORIGIN'] : '*';
            }

            $Call['HTTP']['Headers']['Access-Control-Allow-Origin:'] = $Origin;
        }

        if ('Development' === F::Environment()) {
            $Call['HTTP']['Headers']['X-Codeine-CORS-Enabled:'] = F::Dot($Call, 'HTTP.CORS.Enabled');
        }

        return $Call;
    });