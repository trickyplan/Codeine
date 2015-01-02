<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Open', function ($Call)
    {
        $Server = ssh2_connect($Call['Server']['IP'], $Call['Server']['Port']);
        if ($Server === false)
        {
            F::Log('SSH connect to '.$Call['Server']['IP'].' '.$Call['Server']['Port'].' failed', LOG_ERR);
            $Result['Errors'][] = 'SSH.Connect.Failed';
        }
        else
        {
            F::Log('SSH connected to '.$Call['Server']['IP'].' '.$Call['Server']['Port'], LOG_INFO);

            if (ssh2_auth_password($Server, $Call['Server']['User'], $Call['Server']['Password']))
            {
                F::Log('SSH authenticated as '.$Call['Server']['User'], LOG_INFO);
                return $Server;
            }
            else
            {
                F::Log('SSH authentication as '.$Call['Server']['User'].' failed', LOG_ERR);
                return null;
            }
        }

        return null;
    });

    setFn('Run', function ($Call)
    {
        $Result = [];

        $Server = F::Run(null, 'Open', $Call);

            $Call['Service'] = (array) $Call['Service'];

            $Result = [];

            foreach ($Call['Service'] as $Command)
            {
                F::Log('SSH: '.$Command, LOG_WARNING);
                $Stream = ssh2_exec($Server, $Command);
                $ErrorStream = ssh2_fetch_stream($Stream, SSH2_STREAM_STDERR);

                stream_set_blocking($ErrorStream, true);
                stream_set_blocking($Stream, true);

                $Errors = stream_get_contents($ErrorStream);

                if (empty($Errors))
                    ;
                else
                {
                    F::Log($Errors, LOG_ERR);
                    fclose($ErrorStream);
                }

                $Result[] = trim(stream_get_contents($Stream));
                fclose($Stream);
            }

        return implode(PHP_EOL, $Result);
    });