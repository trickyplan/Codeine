<?php

    setFn('Do', function ($Call)
    {
        $Month = date('m');
        $Day = date('d');
        $Year = date('Y');

        $Start = mktime(0,0,0, $Month, $Day, $Year);
        $End = mktime(0,0,0, $Month, $Day, $Year);

        d(__FILE__, __LINE__, $Start);
        die();
        return [
            'Created' =>
            [
                '$gt' => $Start,
                '$lt' => $End
            ]
        ];
    });