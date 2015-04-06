<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Run', function ($Call)
    {
        $Call = F::Hook('beforeTimetableRun', $Call);

        $Time = time();
        F::Log('Time: '.date('d.m.Y H:i:s', $Time), LOG_DEBUG);

        $Time = [
            'Year' => date('Y', $Time),
            'Month' => date('m', $Time),
            'Day' => date('d', $Time),
            'Hour' => date('H', $Time),
            'Minute' => date('i', $Time),
            'Second' => date('s', $Time)
        ];

        $Components = array_keys($Time);

        F::Log(count($Call['Timetable']['Rules']).' timetable rules loaded', LOG_DEBUG);

        foreach ($Call['Timetable']['Rules'] as $Name => $Rule)
        {
            if (substr($Name, 0, 1) == '-')
                continue;

            $Decision = true;

            foreach ($Components as $Component)
            {
                if (isset($Rule[$Component]))
                {
                    if (is_array($Rule[$Component]))
                        ;
                    else
                        $Rule[$Component] = (array) $Rule[$Component];

                    if (in_array($Time[$Component], $Rule[$Component]))
                        ;
                    else
                        $Decision = false;
                }
            };

            if ($Decision)
            {
                F::Log('Timetable Rule applied '.$Name, LOG_INFO);
                $PID = pcntl_fork();

                if ($PID == -1)
                {
                    F::Log('Daemon: Fork failed', LOG_CRIT);
                    return -1;
                } //TODO: ошибка - не смогли создать процесс
                elseif ($PID)
                {
                    $Ungrateful[$PID] = true;
                    F::Log('Child forked '.$PID, LOG_DEBUG);
                }
                else
                {
                    F::Live($Rule['Run'], $Call);
                    exit(4);
                }
            }

        }

        $Call = F::Hook('afterTimetableRun', $Call);

        return null;
    });