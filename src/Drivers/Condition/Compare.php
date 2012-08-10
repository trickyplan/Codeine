<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Eq', function ($Call)
    {
        return ($Call['A'] > $Call['B']*(1-$Call['Delta'])) && ($Call['A'] < $Call['B']*(1+$Call['Delta']));
    });

    self::setFn('NotEq', function ($Call)
    {
        return ($Call['A'] < $Call['B']*(1-$Call['Delta'])) || ($Call['A'] > $Call['B']*(1+$Call['Delta']));
    });