<?php

    setFn('Do', function ($Call) {

        if (F::Dot($Call, 'HTTP.CORS.Enabled')) {
            $Call['HTTP']['Headers']['Access-Control-Allow-Credentials:'] = F::Dot($Call, 'CORS.Credentials');

            if (F::Dot($Call, 'HTTP.Method') == 'OPTIONS');
            else {
                $Call['HTTP']['Headers']['Access-Control-Allow-Headers: '] = 'X-Requested-With,Content-Type';

                $Origin = F::Dot($Call, 'HTTP.Request.Headers.Origin');

                if (empty($Origin)) {
                    $Origin = (isset($_SERVER['HTTP_ORIGIN'])) ? $_SERVER['HTTP_ORIGIN'] : '*';
                }

                $Call['HTTP']['Headers']['Access-Control-Allow-Origin:'] = $Origin;
            }
        }

        return $Call;
    });