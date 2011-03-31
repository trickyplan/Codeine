<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Object Templater Codeine
     * @package Codeine
     * @subpackage Drivers
     * @version 
     * @date 18.11.10
     * @time 5:46
     */

    self::Fn('Make', function ($Call)
    {
        return $Call['Item']['Data'][$Call['Item']['ID']];
    });
