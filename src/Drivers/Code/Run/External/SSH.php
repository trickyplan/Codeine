<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Open', function ($Call)
    {
        $SSHLinkID = 'SSH:'.$Call['SSH']['Server']['Host'].$Call['SSH']['Server']['Port'];
        
        if ($Call['SSH']['Server']['Link'] = F::Get($SSHLinkID))
            ;
        else
        {
            $Call['SSH']['Server']['Link'] = ssh2_connect($Call['SSH']['Server']['Host'], $Call['SSH']['Server']['Port']);
            
            if ($Call['SSH']['Server']['Link'] === false)
            {
                F::Log('SSH connect to *'.$Call['SSH']['Server']['Host'].':'.$Call['SSH']['Server']['Port'].'* failed', LOG_ERR);
                $Result['Errors'][] = 'SSH.Connect.Failed';
            }
            else
            {
                F::Log('SSH connected to *'.$Call['SSH']['Server']['Host'].':'.$Call['SSH']['Server']['Port'].'*', LOG_INFO);
                
                if ($Call['SSH']['Authentication'] == 'KeyPair')
                    $Call = F::Apply(null, 'Authenticate.KeyPair', $Call);
                else
                    $Call = F::Apply(null, 'Authenticate.Password', $Call);
            }
            
            F::Set($SSHLinkID, $Call['SSH']['Server']['Link']);
        }

        return $Call;
    });

    setFn('Run', function ($Call)
    {
        $Call = F::Apply(null, 'Open', $Call);

            $Call['Service'] = (array) $Call['Service'];

            $Result = [];

            foreach ($Call['Service'] as $Command)
            {
                F::Log('SSH: '.$Command, LOG_WARNING);
                $Stream = ssh2_exec($Call['SSH']['Server']['Link'], $Command);
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
    
    setFn('Authenticate.KeyPair', function ($Call)
    {
        F::Log('Try to authenticate via private keyfile *'.Root.$Call['SSH']['KeyPair']['Private'].'*', LOG_INFO);
        if (ssh2_auth_pubkey_file(  $Call['SSH']['Server']['Link'],
                                    $Call['SSH']['User'],
                                    Root.$Call['SSH']['KeyPair']['Public'],
                                    Root.$Call['SSH']['KeyPair']['Private']))
            F::Log('SSH authenticated as '.$Call['SSH']['User'].' (via keys)', LOG_INFO);
        else
        {
            F::Log('SSH authentication as '.$Call['SSH']['User'].' failed (via keys)', LOG_ERR);
            $Call['SSH']['Server']['Link'] = null;
        }

        return $Call;
    });
    
    setFn('Authenticate.Password', function ($Call)
    {
        if (ssh2_auth_password($Call['SSH']['Server']['Link'], $Call['SSH']['User'], $Call['SSH']['Password']))
            F::Log('SSH authenticated as '.$Call['SSH']['User'].' (via password)', LOG_INFO);
        else
        {
            F::Log('SSH authentication as '.$Call['SSH']['User'].' failed (via password)', LOG_ERR);
            $Call['SSH']['Server']['Link'] = null;
        }

        return $Call;
    });