<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        foreach ($Call['Beautifiers'] as $Rule)
            $Call = F::Apply('Text.Beautifier.'.$Rule, 'Process', $Call);

        return html_entity_decode($Call['Value']);
    });