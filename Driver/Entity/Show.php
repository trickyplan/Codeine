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
        $Call['Layouts'][] = 'Entity/'.$Call['Entity'];

        $Data = Data::Read($Call['Entity'].'::'.$Call['ID']);

        if ($Data)
        {
            $Call['Items']['Object'] = array(
                'UI'        => 'Object',
                'Entity'    => $Call['Entity'],
                'ID'        => $Call['ID'],
                'Style'     => 'Normal',
                'Data'      => $Data[$Call['ID']]);
        }
        return $Call;
    });
