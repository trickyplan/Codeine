<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Show Action
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 10.11.10
     * @time 23:13
     */

    self::Fn('Show', function ($Call)
    {
        var_dump(Code::Run(
            array('F' => 'Process/Hash/Get', 'Input' => 'dd','D'=>'MD5')));
        ;


        $Call['Items'][] = array(
            'UI'        => 'Block',
            'Entity'    => $Call['Entity'],
            'Plugin'    => $Call['Function'],
            'Data'      => 'Hello from block'
        );

        $Call['Items'][] = array(
            'UI'        => 'Object',
            'Entity'    => $Call['Entity'],
            'Plugin'    => $Call['Function'],
            'Data'      => Data::Read(
                array(
                     'Point'=>$Call['Entity'],
                     'Where'=>
                        array(
                            'I'=>$Call['ID'],
                            'K'=>'Key')))
                );
        return $Call;
    });