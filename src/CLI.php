<?php

    /**
     * @author BreathLess
     * @time 5:17
     */

    include 'Core.php';

    $Opts = [];

    foreach ($argv as $arg)
        if (preg_match('/^--(.+)\=(.+)$/Ssu', $arg, $Pockets))
        {
            $Opts = F::Dot($Opts, $Pockets[1], $Pockets[2]);
            F::Log($Pockets[1].' = '.$Pockets[2], LOG_INFO, 'Developer');
        }
        else
            $Opts[] = $arg;

    if (isset($Opts[1]) && file_exists($Opts[1]))
        $Opts = F::Merge(json_decode(file_get_contents($Opts[1]), true), $Opts);

    !defined('Root')? define('Root', getcwd()): false;

    if (empty($Opts))
        ;
    else
    {
        if (isset($Opts['Service']))
        {
            if (isset($Opts['Method']))
                ;
            else
                $Opts['Method'] = 'Do';

            try
            {
                $Call = F::Bootstrap
                    ([
                        'Paths' => [Root],
                        'Environment' => isset($Opts['Environment'])? $Opts['Environment']: null,
                        'Service' => 'System.Interface.CLI',
                        'Method' => 'Do',
                        'Call' => $Opts
                    ]);

                F::Shutdown($Call);
            }
            catch (Exception $e)
            {
               F::Log($e->getMessage(), LOG_CRIT, 'Developer');
            }
        }

        if (isset($Call['Return Code']))
            exit($Call['Return Code']);
        else
            exit(0);
    }

