<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Get', function ($Call)
    {
        return file_get_contents('http://stuff.mit.edu/afs/sipb/contrib/pi/pi-billion.txt');
    });