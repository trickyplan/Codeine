<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Spit', function ($Call)
    {
        F::Log('Calls: '.F::$NC, LOG_INFO);
        F::Log('Memory: '.round(memory_get_usage()/1024).' KiB', LOG_INFO);

        $Logs = F::Logs();

        if (!empty($Logs))
        {
            // $Logs = array_reverse($Logs);
            $Output = '';

            foreach ($Logs as $Channel => $Messages)
                $Output .= F::Run(
                    'IO', 'Write', $Call,
                    [
                        'Storage' => $Channel,
                        'ID' => 'Log: '.$Call['Host'].$Call['URL'],
                        'Data!' => $Messages
                    ]
                );

            $Call['Output'] = str_replace('<logs/>', $Output, $Call['Output']);

            F::Run('IO', 'Close', ['Storage' => 'Developer']);
        }

        return $Call;
    });