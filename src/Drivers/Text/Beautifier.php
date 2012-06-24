<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Process', function ($Call)
    {
        foreach ($Call['Beautifiers'] as $Rule)
            $Call = F::Run('Text.Beautifier.'.$Rule, 'Process', $Call);

        return $Call['Value'];
    });