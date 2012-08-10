<?php

    /* Codeine
     * @author BreathLess
     * @description Access Interface 
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Check', function ($Call)
    {
        return F::Run('Security.Access.'.$Call['System'], 'Check', $Call);
    });

