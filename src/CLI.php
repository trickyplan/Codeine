<?php

    /**
     * @author bergstein@trickyplan.com
     * @time 5:17
     */

    require 'Core.php';
    require 'Codeine/vendor/autoload.php';

    $Opts = [];

    if (isset($argv[1]) && file_exists($argv[1]))
    {
        if ($Opts = F::Merge(jd(file_get_contents($argv[1]), true), $Opts))
            ;//F::Log('JSON CLI parameters loaded from '.$argv[1], LOG_INFO);
    }
    else
    {
        if (preg_match_all('/--([\w.]+)\=([\S]+)/Ssu', $argv[1], $Pockets))
        {
            foreach ($Pockets[1] as $IX => $Key)
            {
                $Key = strtr($Key, '_', ' ');
                $Opts = F::Dot($Opts, $Key, $Pockets[2][$IX]);
                ;//F::Log('Get Opt Style CLI parameter loaded from *'.$Key.'* = *'.$Pockets[2][$IX].'*', LOG_INFO);
            }
        }
        else
            $Opts[] = $argv;
    }

    if (file_exists('/etc/default/codeine'))
        define('Root', file_get_contents('/etc/default/codeine'));

    !defined('Root')? define('Root', getcwd()): false;

    //F::Log('Root folder: '.Root, LOG_INFO);
    include Root.'/vendor/autoload.php';
    
    if (empty($Opts))
        ;//F::Log('Empty CLI parameters', LOG_INFO);
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

