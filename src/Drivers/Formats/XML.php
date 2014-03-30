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
            return json_decode(json_encode(simplexml_load_string($Call['Value'])), true);
        else
            return json_decode(json_encode(simplexml_load_string('<root>'.$Call['Value'].'</root>')), true);
    });