<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Spit', function ($Call)
    {
        $Logs = F::Logs();

        if (!empty($Logs))
        {
            foreach ($Logs as $Call['Channel'] => $Call['Logs'])
            {
                F::Run('IO', 'Write', $Call,
                [
                    'Print Log' => true,
                    'Storage' => $Call['Channel'],
                    'ID' => '['.$Call['Channel'].'] '.$Call['HTTP']['Proto'].$Call['HTTP']['Host'].$Call['HTTP']['URI'],
                    'Data!' => $Call['Logs']
                ]);

                F::Run('IO', 'Close', ['Storage' => $Call['Channel']]);
            }
        }

        return $Call;
    });