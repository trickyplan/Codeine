<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Run', function ($Call)
    {
        $Result = null;

        $Server = ssh2_connect($Call['Server']['IP'], $Call['Server']['Port']);

        if ($Server === false)
            F::Log('SSH connect to '.$Call['Server']['IP'].' '.$Call['Server']['Port'].' failed', LOG_ERR);
        else
        {
            F::Log('SSH connected to '.$Call['Server']['IP'].' '.$Call['Server']['Port'], LOG_INFO);

            if (ssh2_auth_password($Server, $Call['Server']['User'], $Call['Server']['Password']))
            {
                F::Log('SSH authenticated as '.$Call['Server']['User'], LOG_INFO);

                $Stream = ssh2_exec($Server, $Call['Service']);
                $ErrorStream = ssh2_fetch_stream($Stream, SSH2_STREAM_STDERR);

                stream_set_blocking($ErrorStream, true);
                stream_set_blocking($Stream, true);

                $Errors =stream_get_contents($ErrorStream);
                F::Log($Errors, LOG_ERR);

                $Result = stream_get_contents($Stream);
            }
            else
                F::Log('SSH authentication as '.$Call['Server']['User'].' failed', LOG_ERR);
        }

        return $Result;
    });