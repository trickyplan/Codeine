<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (preg_match('/((?:\w+(?:\W+|$)){'.$Call['Words'].'})/Ssu', $Call['Value'], $Words))
            return trim($Words[1]);
        else
            return trim($Call['Value']);
    });