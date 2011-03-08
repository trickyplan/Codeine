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

    self::Fn('Input', function ($Call)
    {
        $Call['Data']['ID'] = $Call['ID'];
        Data::Create(
            array(
                'Point' => $Call['Entity'],
                'Data'  => $Call['Data']
                ));

        return true;
    });

    self::Fn('Form.Create', function ($Call)
    {
        
    });

    self::Fn('Form.Update', function ($Call)
    {
        
    });
