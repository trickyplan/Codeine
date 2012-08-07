<?php

    /* Codeine
     * @author BreathLess
     * @description Date() engine 
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Format', function ($Call)
    {
        $Value = strptime($Call['Value'],'%s');

        $Now = strptime(time(),'%s');

        if (strftime('%U', $Call['Value']) == strftime('%U', time()))
            $Format = '%A';
        else
            $Format = '%d, %B';

        if ($Now['tm_year'] != $Value['tm_year'])
            $Format .= '%G';

        return strftime($Format, $Call['Value']);
     });