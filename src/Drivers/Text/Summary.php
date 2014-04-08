<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Get', function ($Call)
    {
        return F::Run('Text.Cut', 'Do', $Call, ['Cut' => 'Sentences', 'Sentences' => 2]);
    });