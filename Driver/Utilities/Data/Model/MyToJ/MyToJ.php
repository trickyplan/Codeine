<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 28.03.11
     * @time 2:09
     */

    self::Fn('Convert', function ($Call)
    {
        $Model = Code::Run('Data.Model.Reverse.MySQL::Generate({"Table":"'.$Call['Table'].'"})');

        return Data::Create(array(
            'Point' => 'Model',
            'ID' => $Call['Table'],
            'Data' => json_encode($Model)
        ));
    });
