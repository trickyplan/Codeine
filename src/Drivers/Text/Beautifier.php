<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        foreach ($Call['Rules'] as $Rule)
            $Call = F::Run('Text.Beautifier.'.$Rule, 'Process', $Call);

        return $Call;
    });