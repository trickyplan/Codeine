<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
    setFn('Run', function ($Call)
    {
        if (!is_numeric($Call['Parallel']['Threads']))
            $Call['Parallel']['Threads'] = F::Live($Call['Parallel']['Threads'], $Call);

        F::Log('Threads Count: '.$Call['Parallel']['Threads'], LOG_INFO);

        $Call['Data Size'] = count($Call['Data']);
        F::Log('Data size: '.$Call['Data Size'], LOG_INFO);
        $Call['Chunk Size'] = ceil($Call['Data Size'] / $Call['Parallel']['Threads']);
        F::Log('Chunk size: '.$Call['Chunk Size'], LOG_INFO);
        $Call['Data'] = array_chunk($Call['Data'], $Call['Chunk Size'], true);

        $Children = [];
        foreach ($Call['Data'] as $Index => $Chunk)
        {
            $Ungrateful = [];
            $PID = pcntl_fork();

            if ($PID == -1)
            {
                F::Log('Parallel: Fork failed', LOG_CRIT);
                return -1;
            } //TODO: ошибка - не смогли создать процесс
            elseif ($PID)
            {
                $Ungrateful[$PID] = true;
                F::Log('Child forked. PID: '.$PID, LOG_INFO);
                $Children[] = $PID;
            }
            else
            {
                F::Live($Call['Run'], $Call, [$Call['Key'] => $Chunk]);
                exit(0);
            }
        }

        $workers_count = count($Children);
        while ($workers_count > 0) 
        {
            $res = pcntl_wait($status, WNOHANG);
            if ($res == 0) 
            {
                F::Log('Waiting for children: '.$workers_count.' workers left', LOG_INFO);
                sleep(1);
            }
            else
            {
                pcntl_wifexited($status) ? 
                    F::Log("\t\t\tChild exited normally", LOG_INFO) : F::Log("\t\t\tLooks like error happened", LOG_NOTICE);
                $workers_count--;
            }
        }

        $Result = [];
        foreach ($Children as $ChildPID) 
        {
            $ChildResult = F::Run('IO', 'Read', [
                'Storage' => $Call['Results Storage'],
                'Where' => [
                    'ID' => $ChildPID
                ],
                'IO One' => true
            ]);
            $Result[] = $ChildResult;

            F::Run('IO', 'Write', $Call, [
                'Storage' => $Call['Results Storage'],
                'Where' => [
                    'ID' => $ChildPID
                ],
                'Data' => null
            ]);
        }

        return $Result;
    });
