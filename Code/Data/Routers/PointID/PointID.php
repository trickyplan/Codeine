<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Driver for "Point::ID" scheme
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 24.11.10
     * @time 0:08
     */

    self::Fn('Route', function ($Call)
    {
        if (mb_strpos($Call['Input'],'::'))
        {
            $Output = array('Where' => array());
            list($Output['Point'], $Output['Where']['ID']) = explode('::',$Call['Input']);
        }
        else
            $Output = null;

        return $Output;
    });