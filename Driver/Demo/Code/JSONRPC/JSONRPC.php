<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 27.03.11
     * @time 23:04
     */

    self::Fn('Do', function ($Call)
    {
        $Result = Code::Run(
                array(
                    'N' => 'http://www.raboof.com/Projects/Jayrock/Demo.ashx',
                    'F' => 'echo',
                    'id' => '6F9619FF'), Code::Ring2, null, 'RPC.JSON');

        $Call['Items'][] =
            array(
                'UI' => 'Block',
                'Data'=> $Result
            );
        
        return $Call;
    });
