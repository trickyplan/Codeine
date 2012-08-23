<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Write', function ($Call)
    {
        return $Call['Value'];
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });

    self::setFn('Populate', function ($Call)
    {
        return rand().'@one2team.ru';
    });