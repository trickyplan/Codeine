<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'Server',
            'ID' => 'Overview'
        ];

        $Call['Output']['System'][] = shell_exec('uname -a');

        $CPUs = explode(PHP_EOL.PHP_EOL, trim(shell_exec('cat /proc/cpuinfo')));

        $Call['CPU']['Count'] = count($CPUs);

        foreach ($CPUs as $CPU)
        {
            $CPU = explode(PHP_EOL, $CPU);
            $CPUData = [];
            foreach ($CPU as $Line)
            {
                list($Key, $Value) = explode(':', $Line);
                $CPUData[] = [$Key, $Value];
            }

            $Call['Output']['CPU'][] =
                [
                    'Type'  => 'Table',
                    'Value' => $CPUData
                ];
        }

        return $Call;
    });

    setFn('Benchmark', function ($Call)
    {
        return F::Run('Server.Benchmark', 'Do', $Call);
    });

    setFn('Environment', function ($Call)
    {
        $Data = [];
        
        foreach ($_SERVER as $Key => $Value)
            $Data[] = [$Key, $Value];

        $Call['Output']['Content'][]
        =
        [
            'Type' => 'Table',
            'Value' => $Data
        ];
        return $Call;
    });