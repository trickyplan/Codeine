<?php

    /**
     * @author bergstein@trickyplan.com
     * @time 5:17
     */

    require 'Core.php';

    fwrite(STDERR, "\033[1;31mCodeine CLI started \033[0m".PHP_EOL);

    $Opts = [];

    if (isset($argv[1]))
    {
        if (file_exists($argv[1]))
            if ($Opts = F::Merge(jd(file_get_contents($argv[1]), true), $Opts))
                F::Log('JSON CLI parameters loaded from ' . $argv[1], LOG_INFO);

        if (preg_match_all('/--([\w.]+)\=([\S]+)/Ssu', $argv[1], $Pockets))
        {
            foreach ($Pockets[1] as $IX => $Key)
            {
                $Key = strtr($Key, '_', ' ');
                $Opts = F::Dot($Opts, $Key, $Pockets[2][$IX]);
                F::Log('Get Opt Style CLI parameter loaded from *' . $Key . '* = *' . $Pockets[2][$IX] . '*', LOG_INFO);
            }
        }
        else
            $Opts[] = $argv;

        !defined('Root') ? define('Root', getcwd()) : false;

        fwrite(STDERR, "\033[1;31mRoot: \033[0m".Root.PHP_EOL);
        include Root . '/vendor/autoload.php';

        if (empty($Opts))
            ;//F::Log('Empty CLI parameters', LOG_INFO);
        else
        {
            if (isset($Opts['Service']))
            {
                fwrite(STDERR, "\033[1;31mService: \033[0m".$Opts['Service'].PHP_EOL);
                if (isset($Opts['Method']))
                    ;
                else
                    $Opts['Method'] = 'Do';

                fwrite(STDERR, "\033[1;31mMethod: \033[0m".$Opts['Method'].PHP_EOL);

                try
                {
                    $Call = F::Bootstrap
                    ([
                        'Paths' => [Root],
                        'Environment' => isset($Opts['Environment']) ? $Opts['Environment'] : null,
                        'Service' => 'System.Interface.CLI',
                        'Method' => 'Do',
                        'Call' => $Opts
                    ]);

                    F::Shutdown($Call);
                } catch (Exception $e)
                {
                    F::Log('CLI Exception: ' . $e->getMessage(), LOG_CRIT, 'Developer');
                }
            }

            if (isset($Call['Return Code']))
                exit($Call['Return Code']);
            else
                exit(0);
        }
    }