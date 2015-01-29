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
            return count($Call['Data'][$Call['Key']]);
        else
            return 0;
    });

    setFn('CountWithOutSpaces', function ($Call)
    {
        if (isset($Call['Data'][$Call['Key']]))
            return mb_strlen(strtr(strip_tags($Call['Data'][$Call['Key']]),[' ' => '',"\n" => '']));
        else
            return 0;
    });