<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Run', function ($Call)
    {
        $Command = 'xvfb-run slimerjs --ssl-protocol=any '.$Call['Script'].' '.$Call['Where']['ID'];
        F::Log($Command, LOG_INFO);
        exec($Command, $Result, $Return);
        F::Log($Return, LOG_INFO);
        return $Result;
    });