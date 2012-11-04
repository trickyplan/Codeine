<?php

    /* Codeine
     * @author BreathLess
     * @description Access Interface 
     * @package Codeine
     * @version 7.x
     */

    setFn('Check', function ($Call)
    {
        return F::Run('Security.Access.'.$Call['System'], 'Check', $Call);
    });

