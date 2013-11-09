<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        foreach ($Call['Terms'] as $Term)
            $Call['Output']['Content'][] =
            [
                'Type'  => 'Heading',
                'Level' => 3,
                'Value' => '<l>Project.Terms:Rule.'.$Term.'</l>'
            ];

        return $Call;
    });

    setFn('RAW', function ($Call)
    {
        return $Call['Terms'];
    });