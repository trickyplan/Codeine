<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Mark', function ($Call)
    {
        $Call['Data']['EV'] = $Call['EV'];

        return $Call;
    });