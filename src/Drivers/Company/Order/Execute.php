<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        F::Run('Entity', 'Read', $Call);

        $Call = F::Apply('Server.OS.Determine', null, $Call);

        return $Call;
    });