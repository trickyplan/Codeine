<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Flatfile
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 23.11.10
     * @time 20:49
     */

    self::Fn('Connect', function ($Call)
    {
        return $Call;
    });

    self::Fn('Read', function ($Call)
    {
        return file_get_contents(Root.$Call['Where']['ID']);
    });

    self::Fn('Create', function ($Call)
    {
        return file_put_contents(Root.$Call['Data']['ID'], $Call['Data']['Body']);
    });