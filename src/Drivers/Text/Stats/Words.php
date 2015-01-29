<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Count', function ($Call)
    {
        if (isset($Call['Data'][$Call['Key']]))
        {
            $Text = strip_tags($Call['Data'][$Call['Key']]);
            return count(preg_split('~[\s0-9_]|[^\w]~u', $Text, -1, PREG_SPLIT_NO_EMPTY));
        }
        else
            return 0;
    });