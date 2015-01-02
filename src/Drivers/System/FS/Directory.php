<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn ('Create', function ($Call)
        {
            return mkdir ($Call['Value']);
        });

    setFn ('Change', function ($Call)
        {
            return chdir($Call['Value']);
        });