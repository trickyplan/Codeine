<?php

    /**
     * @author bergstein@trickyplan.com
     * @time 5:17
     */

    include 'Core.php';
    
    $Opts = [];

    foreach ($argv as $arg)
        if (preg_match('/^--([^=]+)\=(.+)$/Ssu', $arg, $Pockets))
        {
            $Opts = F::Dot($Opts, strtr($Pockets[1], '_', ' '), $Pockets[2]);
            F::Log($Pockets[1].' = '.$Pockets[2], LOG_INFO);
        }
        else
            $Opts[] = $arg;

    if (isset($Opts[1]) && file_exists($Opts[1]))
    {
        if ($Opts = F::Merge(jd(file_get_contents($Opts[1]), true), $Opts))
            F::Log('JSON CLI parameters loaded from '.$Opts[1], LOG_INFO);
    }

    if (file_exists('/etc/default/codeine'))
        define('Root', file_get_contents('/etc/default/codeine'));

    !defined('Root')? define('Root', getcwd()): false;

    F::Log('Root folder: '.Root, LOG_INFO);
    if (empty($Opts))
        F::Log('Empty CLI parameters', LOG_INFO);
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

