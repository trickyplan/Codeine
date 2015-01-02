<?php

    /* Codeine
     * @author BreathLess
     * @description Date() engine 
     * @package Codeine
     * @version 8.x
     */

    setFn('Format', function ($Call)
    {
        $Value = strptime($Call['Value'],'%s');

        $Now = strptime(time(),'%s');

        if (strftime('%U', $Call['Value']) == strftime('%U', time()))
            $Format = '%A';
        else
            $Format = '%B, %d';

        if ($Now['tm_year'] != $Value['tm_year'])
            $Format .= '%G';

        $Format.= ', %l:%M';

        return mb_strtolower(strftime($Format, $Call['Value']));
     });