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
        {
            $OutputTemp = F::Dot($Call['Data'], $Key);
            $Output[] = is_array($OutputTemp)?implode($Call['Glue'], $OutputTemp):$OutputTemp;
        }
        $Output = implode($Call['Glue'], $Output);

        if (isset($Call['Hash']))
            $Output = sha1($Output);

        return $Output;
    });
