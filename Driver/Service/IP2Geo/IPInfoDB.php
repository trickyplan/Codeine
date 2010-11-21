<?php

    function F_IPInfoDB_Get($IP)
    {
        $Response = json_decode(file_get_contents('http://www.ipinfodb.com/ip_query.php?output=json&ip='.$IP), true);
        
        $Output = array();

        $Assoc = array('Town'=>'City',
                       'Region'=>'RegionName',
                       'Country'=>'CountryCode',
                       'Latitude'=>'Latitude',
                       'Longitude'=>'Longitude',
                       'Timezone'=>'Timezone',
                       'GMTOffset'=>'Gmtoffset',
                       'DSTOffset'=>'Dstoffset');
        
        foreach ($Assoc as $To => $From)
            $Output[$To] = $Response[$From];

        return $Output;
    }
