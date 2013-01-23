<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Count', function ($Call)
    {
        $Text = strip_tags($Call['Data'][$Call['Key']]);

        return count(preg_split('~[\s0-9_]|[^\w]~u', $Text, -1, PREG_SPLIT_NO_EMPTY));
    });