<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    self::setFn('Get', function ($Call)
    {
        return trim(file_get_contents('http://www.random.org/integers/?num=1&min='.$Call['Min'].'&max='.$Call['Max'].'&col=1&base=10&format=plain&rnd=new'));
    });