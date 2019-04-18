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
        
        if (empty($Call['All Logs']))
            ;
        else
        {
            foreach ($Call['All Logs'] as $Call['Channel'] => $Call['Channel Logs'])
            {
                if (empty($Call['Channel Logs']))
                    ;
                else
                {
                    $Call = F::Hook('beforeLogSpit', $Call);
                        
                            F::Run('IO', 'Write', $Call,
                                [
                                    'Storage' => $Call['Channel'],
                                    'Where!'  => '[' . $Call['Channel'] . '] ' . $Call['HTTP']['Proto'] . $Call['HTTP']['Host'] . $Call['HTTP']['URI'],
                                    'Data!'   => $Call['Channel Logs']
                                ]);
                            
                            F::Run('IO', 'Close', ['Storage' => $Call['Channel']]);
                        
                    $Call = F::Hook('afterLogSpit', $Call);
                }
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
