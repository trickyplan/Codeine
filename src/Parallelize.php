<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    include 'Core.php';

    $Values = [];

    for ($Z = 1; $Z<200000; $Z++)
        $Values[] = rand(0, 100000);

    $Values = array_chunk($Values, count($Values)/8);

    foreach ($Values as $Value)
    {
        $PID = pcntl_fork();

        if ($PID == -1)
        {
            F::Log('Daemon: Fork failed', LOG_CRIT);
            return -1;
        } //TODO: ошибка - не смогли создать процесс
        elseif ($PID)
        {
            F::Log('Child forked '.$PID, LOG_INFO);
            $Ungrateful[] = $PID;
        }
        else
        {
            unset ($Values);
            sleep(5);
            break;
        }
    }

    pcntl_wait($Status);