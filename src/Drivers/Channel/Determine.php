<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Channel', function ($Call)
    {
        $Channel = null;
        if (isset($Call['Session']['User']['Channel']))
            $Channel = $Call['Session']['User']['Channel'];
        elseif (isset($Call['Session']['Channel']))
            $Channel = $Call['Session']['Channel'];
        elseif (isset($Call['Request']['Channel']))
            {
                $Channel = $Call['Request']['Channel'];
                F::Log('Channel determined by request *'.$Channel.'*', LOG_INFO, 'Marketing');
            }
            elseif (isset($_SERVER['HTTP_REFERER']))
                {
                    $Referrer  = parse_url($_SERVER['HTTP_REFERER']);
                    $Channel = $Referrer['host'];
                    F::Log('Channel determined by referrer host *'.$Channel.'*', LOG_INFO, 'Marketing');
                }

        return $Channel;
    });

    setFn('Subchannel', function ($Call)
    {
        $Subchannel = null;
        if (isset($Call['Session']['User']['Subchannel']))
            $Subchannel = $Call['Session']['User']['Subchannel'];
        elseif (isset($Call['Session']['Subchannel']))
            $Subchannel = $Call['Session']['Subchannel'];
        elseif (isset($Call['Request']['Subchannel']))
            {
                $Subchannel = $Call['Request']['Subchannel'];
                F::Log('Subchannel determined by request *'.$Subchannel.'*', LOG_INFO, 'Marketing');
            }
            elseif (isset($_SERVER['HTTP_REFERER']))
                {
                    $Referrer  = parse_url($_SERVER['HTTP_REFERER']);
                    $Subchannel = $Referrer['host'];
                    F::Log('Subchannel determined by referrer host *'.$Subchannel.'*', LOG_INFO, 'Marketing');
                }

        return $Subchannel;
    });