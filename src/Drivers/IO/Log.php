<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Spit', function ($Call)
    {
        F::Log('Calls: '.self::$NC, LOG_INFO);
        F::Log('Memory: '.round(memory_get_usage()/1024).' KiB', LOG_INFO);

        $Logs = F::Logs();

        if (!empty($Logs))
        {
            foreach ($Logs as $Call['Channel'] => $Call['Logs'])
            {
                F::Run('IO', 'Write', $Call,
                    [
                        'Storage' => $Call['Channel'],
                        'ID' => $Call['URL'],
                        'Data' => F::Run('Formats.Log.'.$Call['Log Format'], 'Do', $Call)
                    ]);

                F::Run('IO', 'Close', ['Storage' => $Call['Channel']]);
            }
        }

        return $Call;
    });