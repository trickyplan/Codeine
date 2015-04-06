<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Run', function ($Call)
    {
        if (isset($Call['Script']))
            ;
        else
            $Call['Script'] = '';

        if (isset($Call['Proxy']))
            $Proxy = '--proxy='.$Call['Proxy'];
        else
        {
            if (isset($Call['Random Proxy']))
            {
                $Call['Proxies'] = F::Live($Call['Proxies']);
                $Call['Proxy'] = $Call['Proxies'][array_rand($Call['Proxies'])];
                $Proxy = '--proxy='.$Call['Proxy'].' --proxy-type=http';
            }
            else
                $Proxy = '';
        }

        $Command = 'xvfb-run --auto-servernum --server-num=100 slimerjs '.$Proxy.' --ssl-protocol=any '.$Call['Script'].' '.$Call['Where']['ID'];
        F::Log($Command, LOG_INFO);
        // exec($Command, $Result, $Return);
        $Result = shell_exec($Command);
        F::Log('Size of Slimer output: '.mb_strlen($Result), LOG_INFO);
        // F::Log($Return, LOG_INFO);
        return [$Result];
    });