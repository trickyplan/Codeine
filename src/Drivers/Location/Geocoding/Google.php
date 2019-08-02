<?php

    setFn('Decode', function ($Call)
    {
        // https://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&key=YOUR_API_KEY

        if (F::Dot($Call, 'Location.Geocoding.Google.Key'))
            $Result = F::Run('IO', 'Read', $Call,
                [
                    'Storage'       => 'Web',
                    'Format' => 'Formats.JSON',
                    'Where'         => F::Dot($Call, 'Location.Geocoding.Google.Endpoint'),
                    'Data'          =>
                        [
                            'address'    => $Call['Address'],
                            'key'       => F::Dot($Call, 'Location.Geocoding.Google.Key')
                        ]
                ]);
        else
            F::Log('Theres is no Google Geocoding API Key provided', LOG_ERR);

        return $Result;
    });