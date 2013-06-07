<?php

    /**
     * @author BreathLess
     * @date 27.28.11
     * @time 5:17
     */


    if (file_exists(Root.'/locks/down') && !isset($_COOKIE['Magic']))
    {
        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Status: 503 Service Temporarily Unavailable');

        if (file_exists('down.html'))
            readfile('down.html');
        else
            readfile('Codeine/down.html');
    }
    else
    {
        include 'Codeine/Core.php';

        F::Bootstrap ([
                        'Path' => [Root]
                      ]);

        try
        {
            $Call = F::Run(
                'System.Interface.Web',
                'Run',
                [
                    'Service' => 'Code.Flow.Front',
                    'Method'  => 'Run'
                ]
            );
        }
        catch (Exception $E)
        {
            F::Error($E->getCode(), $E->getMessage(), $E->getFile(), $E->getLine(), $E->getTrace());
        }

    }