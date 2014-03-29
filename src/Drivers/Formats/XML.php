<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Read', function ($Call)
    {
        return json_decode(json_encode(simplexml_load_string($Call['Value'])), true);
    });