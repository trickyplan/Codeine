<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Read', function ($Call)
    {
        if (preg_match('/<\?xml/', $Call['Value']))
            return jd(j(simplexml_load_string($Call['Value'])), true);
        else
            return jd(j(simplexml_load_string('<root>'.$Call['Value'].'</root>')), true);
    });