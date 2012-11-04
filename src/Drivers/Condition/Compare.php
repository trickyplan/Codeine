<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Eq', function ($Call)
    {
        return ($Call['A'] > $Call['B']*(1-$Call['Delta'])) && ($Call['A'] < $Call['B']*(1+$Call['Delta']));
    });

    setFn('NotEq', function ($Call)
    {
        return ($Call['A'] < $Call['B']*(1-$Call['Delta'])) || ($Call['A'] > $Call['B']*(1+$Call['Delta']));
    });