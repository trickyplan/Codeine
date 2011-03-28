<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Model Engine
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 20.02.11
     * @time 20:33
     */

    self::Fn('Validate', function ($Call)
    {
        return true;
    });

    self::Fn('Create', function ($Call)
    {
        $Call['Data']['ID'] = $Call['ID'];
        return Data::Create(
            array(
                'Point' => $Call['Entity'],
                'Data'  => $Call['Data']
                ));
    });

    self::Fn('Update', function ($Call)
    {
        $Data = Data::Read($Call['Entity'].'::'.$Call['ID']);

        Data::Update(
            array(
                'Point' => $Call['Entity'],
                'Where'  => array(
                    'ID' => $Call['ID']
                ),
                'Set'  => array_diff_assoc($Call['Data'], $Data[$Call['ID']])
                ));
    });

    self::Fn('Form.Create', function ($Call)
    {
        
    });

    self::Fn('Form.Update', function ($Call)
    {
        
    });
