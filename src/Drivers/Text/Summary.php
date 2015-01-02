<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Get', function ($Call)
    {
        return F::Run('Text.Cut', 'Do', $Call, ['Cut' => 'Sentences', 'Sentences' => 2]);
    });