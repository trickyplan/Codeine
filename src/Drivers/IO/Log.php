<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Spit', function ($Call)
    {
        $Logs = F::Logs();

        if (empty($Logs))
            ;
        else
        {
            foreach ($Logs as $Call['Channel'] => $Call['Logs'])
            {
                if (empty($Call['Logs']))
                    ;
                else
                {
                    if (F::Dot($Call, 'Log.Sorted') === true)
                        $Call['Logs'] = F::Sort($Call['Logs'], 0);
                        
                    F::Run('IO', 'Write', $Call,
                        [
                            'Storage'   => $Call['Channel'],
                            'Where!'     => '['.$Call['Channel'].'] '.$Call['HTTP']['Proto'].$Call['HTTP']['Host'].$Call['HTTP']['URI'],
                            'Data!'     => $Call['Logs']
                        ]);
                }

                F::Run('IO', 'Close', ['Storage' => $Call['Channel']]);
            }
        }

        return $Call;
    });

    setFn('Autotest', function ($Call)
    {
        F::Log('Autotest. I\'m alive.', LOG_WARNING, 'All');
        return $Call;
    });

    setFn('Hook', function ($Call)
    {
        F::Log(F::Live($Call['Message'], $Call), $Call['Verbose'], $Call['Channel']);
        return $Call;
    });
