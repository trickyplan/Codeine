<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: RAW View Controller
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 12.02.11
     * @time 13:30
     */

    self::Fn('View', function ($Call)
    {
        $Call['Items'] = array();
        
        $Call['Items']['Object'] = array(
            'UI'        => 'VTable',
            'Entity'    => $Call['Entity'],
            'ID'        => $Call['ID'],
            'Plugin'    => $Call['F'],
            'Data'      => Data::Read(
                                    array(
                                         'Point'=> $Call['Entity'],
                                         'Where'=>
                                            array(
                                                'ID'=>$Call['ID'])))
                                    );

        return $Call;
    });
