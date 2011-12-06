<?php

    /* Codeine
     * @author BreathLess
     * @description: Static Routing Strategy
     * @package Codeine
     * @version 6.0
     * @date 09.07.11
     * @time 22:11
     */

    self::setFn('List', function ($Call)
    {
        return array('JSON', 'Data', 'Object', 'Message');
    });
