<?php

    setFn('Do', function ($Call)
    {
        if (F::Dot($Call, 'HTTP.CORS.Enabled'))
        {
            $Call['HTTP']['Headers']['Access-Control-Allow-Credentials:'] = F::Dot($Call, 'CORS.Credentials');

            $Call['HTTP']['Headers']['Access-Control-Allow-Headers: '] = 'X-Requested-With,Content-Type';

            $Origin = F::Dot($Call, 'HTTP.Request.Headers.Origin');

            if (empty($Origin))
                $Origin = (isset($_SERVER['HTTP_ORIGIN'])) ? $_SERVER['HTTP_ORIGIN'] : '*';

            $Call['HTTP']['Headers']['Access-Control-Allow-Origin:'] = $Origin;
        }
        
        if ('Development' === F::Environment())
            $Call['HTTP']['Headers']['X-Codeine-CORS-Enabled:'] = F::Dot($Call, 'HTTP.CORS.Enabled');

        return $Call;
    });