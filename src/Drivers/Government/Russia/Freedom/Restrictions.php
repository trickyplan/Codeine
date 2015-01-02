<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['Freedom']['URLs'][$Call['HTTP']['URL']]))
        {
            $Call = F::Apply('Error.451', 'Page', $Call);
            // TODO Country test
        }

        return $Call;
    });