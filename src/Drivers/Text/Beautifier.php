<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        foreach ($Call['Beautifiers'] as $Rule)
            $Call = F::Run('Text.Beautifier.'.$Rule, 'Process', $Call);

        return $Call['Value'];
    });