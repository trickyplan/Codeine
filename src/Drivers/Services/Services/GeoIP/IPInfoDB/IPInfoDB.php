<?php

    /* Codeine
     * @author BreathLess
     * @description: IPInfoDB Service
     * @package Codeine
     * @version 6.0
     * @date 18.11.10
     * @time 2:42
     */


    self::Fn('Get', function ($Call)
    {
        $Response = json_decode(
            file_get_contents('http://www.ipinfodb.com/ip_query.php?output=json&ip='.$Call['IP']), true);

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
    });
