<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Get', function ($Call)
    {
        $finfo = new finfo(FILEINFO_MIME);
        list($Type) = explode(';', $finfo->buffer($Call['Data']));

        if (isset($Call['Map'][$Type]))
            return F::Live($Call['ID']).$Call['Map'][$Type];
        else
            return null;
    });