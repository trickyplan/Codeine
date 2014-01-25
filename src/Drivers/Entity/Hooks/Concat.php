<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Output = [];

        foreach ($Call['Keys'] as $Key)
            if (isset($Call['Data'][$Key]))
                $Output[] = $Call['Data'][$Key];

        $Output = implode($Call['Glue'], $Output);

        if (isset($Call['Hash']))
            $Output = sha1($Output);

        return $Output;
    });