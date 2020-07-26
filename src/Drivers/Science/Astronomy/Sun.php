<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Info', function ($Call)
    {
        $Output = [];

        if (isset($Call['Time']))
            ;
        else
            $Call['Time'] = F::Run('System.Time', 'Get');

        if (F::Dot($Call, 'Latitude') && F::Dot($Call, 'Longitude'))
        {
            $Data = date_sun_info(time()
                , F::Dot($Call, 'Latitude')
                , F::Dot($Call, 'Longitude')
            );

            $Map = F::Dot($Call, 'Science.Astronomy.Sun.Map');

            foreach ($Map as $From => $To)
                $Output = F::Dot($Output, $To, $Data[$From]);

            $Output = F::Dot($Output, 'Daylight', $Data['sunset']-$Data['sunrise']);
        }
        return $Output;
    });