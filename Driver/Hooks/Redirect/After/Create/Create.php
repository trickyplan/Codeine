<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 08.03.11
     * @time 3:29
     */

    self::Fn('Redirect', function ($Call)
    {
        return Code::Run(
            array(
                'N' => 'Client.Redirect',
                'F' => 'Do',
                'URL' => '/Show/'.$Call['Data']['Entity'].'/'.$Call['Data']['ID']
            )
        );
    });
