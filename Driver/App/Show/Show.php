<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Show Action
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 10.11.10
     * @time 23:13
     */

    self::Fn('Show', function ($Call)
    {
        $Call['Items'] = array();

        $Call['Items']['Object'] = array(
            'UI'        => 'Object',
            'Entity'    => $Call['Entity'],
            'ID'        => $Call['ID'],
            'Plugin'    => $Call['F'],
            'Data'      => Data::Read(
                                    array(
                                         'Point'=>$Call['Entity'],
                                         'Where'=>
                                            array(
                                                'ID'=>$Call['ID'])))
                                    );

        return $Call;
    });
