<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Linear Congruent Generator
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 23:59
     */

    self::Fn('Generate', function ($Call)
    {
        return lcg_value($Call['Min'], $Call['Max']);
    });