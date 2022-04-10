<?php

    use GeoIp2\Database\Reader;

    setFn('Determine', function ($Call) {
        $Unified = [];
        $Reader = new Reader(Root . '/src/Storage/GeoLite2-City.mmdb');
        $Record = $Reader->city($Call['HTTP']['IP']);

        $Unified['ZIP'] = $Record->postal->code;
        $Unified['City']['Title'] = $Record->city->name;
        $Unified['Country']['Title'] = $Record->country->name;
        $Unified['Country']['Code'] = $Record->country->isoCode;
        $Unified['Region']['Title'] = $Record->subdivisions[0]->name;
        $Unified['Region']['Code'] = $Record->subdivisions[0]->isoCode;
        $Unified['Location']['Latitude'] = $Record->location->latitude;
        $Unified['Location']['Longtiude'] = $Record->location->longitude;
        $Unified['Timezone'] = $Record->location->timeZone;

        return $Unified;
    });
