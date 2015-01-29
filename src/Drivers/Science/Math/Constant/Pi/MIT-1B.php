<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Get', function ($Call)
    {
        return file_get_contents('http://stuff.mit.edu/afs/sipb/contrib/pi/pi-billion.txt');
    });