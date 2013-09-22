<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        $Call['Value'] = $Call['Scope'].':'.$Call['Data']['ID'];

        return $Call;
    });