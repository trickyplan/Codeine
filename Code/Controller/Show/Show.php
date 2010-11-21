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

    $Show = function ($Call)
    {
        Code::Run(array(
                       'F' => 'Meta/Source/Generate/Generate',
                       'D' => 'MCrypt',
                       'Version' => '0.1',
                       'Description' => 'Driver'
                  ));

        Data::Mount ($Call['Entity']);

        $Object['UI']  = 'Object'; // Form?
        $Object['Entity'] = $Call['Entity'];
        $Object['Plugin'] = $Call['Plugin'];
        $Object['Data'] = Data::Read($Call['Entity'], array('I'=>$Call['ID'], 'K'=>'Key'));

        $Call['Items'][0] = $Object;
        return $Call;
    };