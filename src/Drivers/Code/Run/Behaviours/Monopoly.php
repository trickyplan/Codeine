<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        $Result = null;
        $Checking = false;

        if ($Keys = F::Dot($Call, 'Behaviours.Monopoly.Keys'))
        {
            $Hash = [];
            foreach ($Keys as $Key)
                $Hash[] = F::Dot($Call['Run']['Call'], $Key);

            $sHash = serialize($Hash);
        }
        else
            $sHash = 'All';

        $Scope = $Call['Run']['Service'].'/'.$Call['Run']['Method'];
        $LockID = F::Dot($Call, 'Behaviours.Monopoly.Hash.Algo').'.'.hash(F::Dot($Call, 'Behaviours.Monopoly.Hash.Algo'), $Scope.$sHash);

        do
        {
            $Lock = F::Run('IO', 'Read',
            [
                'Storage'   => 'Locks',
                'Scope'     => $Scope,
                'Where'     => ['ID' => $LockID],
                'Time'      => microtime(true),
                'IO One'    => true
            ]);

            if ($Lock === null)
            {
                $Run = true;
                F::Log('Lock is not found', LOG_INFO);
            }
            else
            {
                if (file_exists( '/proc/'.$Lock['PID'] ))
                {
                    F::Log('Lock is found and its owner *'.$Lock['PID'].'* still alive', LOG_NOTICE);
                    $Run = false;
                }
                else
                {
                    F::Log('Lock is found but its owner *'.$Lock['PID'].'* is dead', LOG_NOTICE);
                    $Run = true;
                }
            }

            if ($Run)
            {
                F::Run('IO', 'Write',
                [
                    'Storage'   => 'Locks',
                    'Scope'     => $Scope,
                    'Where'     => ['ID' => $LockID],
                    'Data'      =>
                    [
                        'Time' => microtime(true),
                        'PID'  => posix_getpid()
                    ]
                ]);

                F::Log('Lock '.$LockID.' is enabled', LOG_INFO);

                $Result = F::Run($Call['Run']['Service'], $Call['Run']['Method'], $Call['Run']['Call'], ['Behaviours' => $Call['Behaviours']]);

                F::Run('IO', 'Write',
                [
                    'Storage'   => 'Locks',
                    'Scope'     => $Scope,
                    'Where'     => ['ID' => $LockID],
                    'Data'      => null
                ]);
                F::Log('Lock '.$LockID.' is disabled', LOG_INFO);
                $Checking = false;
            }
            else
            {
                F::Log('Lock '.$LockID.' is active', LOG_INFO);

                switch (F::Dot($Call, 'Behaviours.Monopoly.IfLocked'))
                {
                    case 'Wait':
                        $Pause = F::Dot($Call, 'Behaviours.Monopoly.WaitForMicroseconds');
                        F::Log('Waiting for '.$Pause.' microseconds', LOG_INFO);
                        $Checking = true;
                        usleep($Pause);
                    break;

                    case 'Skip':
                        F::Log('Skipping Run', LOG_INFO);
                        $Checking = false;
                    break;
                }
            }
        }
        while ($Checking);

        $Call['Run']['Result'] = $Result;

        return $Call;
    });