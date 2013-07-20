<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Output']['System'][] = shell_exec('uname -a');

        $CPUs = explode(PHP_EOL, trim(shell_exec('cat /proc/cpuinfo | grep "model name"')));

        foreach ($CPUs as $IX => $CPU)
        {
            list(,$CPU) =  explode(':', $CPU);
            $Call['Output']['CPU'][] = ($IX+1).' '.$CPU.PHP_EOL;
        }

        return $Call;
    });

    setFn('Benchmark', function ($Call)
    {
        return F::Run('Server.Benchmark', 'Do', $Call);
    });