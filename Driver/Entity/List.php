<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: List Action
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 10.11.10
     * @time 23:13
     */

    self::Fn('List', function ($Call)
    {
        $Call['Items'] = array();

        $Call['Layouts'][] = 'Entity/'.$Call['Entity'];
        
        $List = Data::Read(array('Point' => $Call['Entity']));
        
        foreach ($List as $ID => $Item)
            $Call['Items'][] = array(
                'UI'        => 'Object',
                'Entity'    => $Call['Entity'],
                'ID'        => $ID,
                'Style'     => 'Short',
                'Data'      => $Item);

        return $Call;
    });
