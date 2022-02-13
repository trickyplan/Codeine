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

            if (isset($Call['HTTP']))
                $App = $Call['HTTP']['Proto'] . $Call['HTTP']['Host'] . $Call['HTTP']['URI'];
            else
                $App = $Call['Service'] . ':' . $Call['Method'];

            $Codeine = F::loadOptions('Codeine');
            $Channels = F::Dot($Codeine, 'Verbose');

            foreach ($Channels as $Call['Channel'] => $Verbose)
            {
                if (isset($Call['All Logs'][$Call['Channel']]))
                {
                    $Call['Channel Logs'] = $Call['All Logs'][$Call['Channel']];

                    if (empty($Call['Channel Logs']))
                        ;
                    else
                    {
                        $Call = F::Hook('Log.Spit.Channel.Before', $Call);

                        F::Run('IO', 'Write', $Call,
                            [
                                'Storage' => $Call['Channel'],
                                'Where!' => '[' . $Call['Channel'] . '] ' . $App,
                                'Data!' => $Call['Channel Logs']
                            ]);

                        F::Run('IO', 'Close', ['Storage' => $Call['Channel']]);

                        $Call = F::Hook('Log.Spit.Channel.After', $Call);
                    }
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
