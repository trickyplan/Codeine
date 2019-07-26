<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Spit', function ($Call)
    {
        $Call['All Logs'] = F::Logs();

        $Call = F::loadOptions('IO', null, $Call);

        if (empty($Call['All Logs']))
            ;
        else
        {
            $Call = F::Hook('Log.Spit.Before', $Call);

                foreach ($Call['All Logs'] as $Call['Channel'] => $Call['Channel Logs'])
                {
                    if (empty($Call['Channel Logs']))
                        ;
                    else
                    {
                        $Call = F::Hook('Log.Spit.Channel.Before', $Call);

                            F::Run('IO', 'Write', $Call,
                                [
                                    'Storage' => $Call['Channel'],
                                    'Where!'  => '[' . $Call['Channel'] . '] ' . $Call['HTTP']['Proto'] . $Call['HTTP']['Host'] . $Call['HTTP']['URI'],
                                    'Data!'   => $Call['Channel Logs']
                                ]);

                            F::Run('IO', 'Close', ['Storage' => $Call['Channel']]);

                        $Call = F::Hook('Log.Spit.Channel.After', $Call);
                    }
                }

            $Call = F::Hook('Log.Spit.After', $Call);
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
