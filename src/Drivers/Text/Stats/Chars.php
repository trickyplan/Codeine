<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Count', function ($Call)
    {
        return count($Call['Data'][$Call['Key']]);
    });

    setFn('CountWithOutSpaces', function ($Call)
    {
        return mb_strlen(strtr(strip_tags($Call['Data'][$Call['Key']]),[' ' => '',"\n" => '']));
    });