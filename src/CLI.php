<?php

    require 'Core.php';

    // log is not available before bootstrap
    fwrite(STDERR, 'Codeine CLI started' . PHP_EOL);

    $Opts = [];

    if (isset($argv[1])) {
        // /usr/bin/codeine Run.json
        if (file_exists($argv[1])) {
            if ($Opts = F::Merge(jd(file_get_contents($argv[1])), $Opts)) {
                // log is not available before bootstrap
                fwrite(STDERR, "JSON CLI parameters loaded from \033[0;32m" . $argv[1] . "\033[0m" . PHP_EOL);
            }
        }

        if (preg_match_all('/--([\w.]+)\=([\S]+)/Ssu', implode(' ', $argv), $Pockets)) {
            foreach ($Pockets[1] as $IX => $Key) {
                $Key = strtr($Key, '_', ' ');
                $Opts = F::Dot($Opts, $Key, $Pockets[2][$IX]);
                // log is not available before bootstrap
                fwrite(
                    STDERR,
                    "Get Opt Style CLI parameter loaded from \033[1;32m" . $Key . "\033[0m = \033[1;34m" . $Pockets[2][$IX] . "\033[0m" . PHP_EOL
                );
            }
        } else {
            $Opts[] = $argv;
        }

        !defined('Root') ? define('Root', getcwd()) : false;

        // log is not available before bootstrap
        fwrite(STDERR, "Root: \033[1;32m" . Root . "\033[0m" . PHP_EOL);
        include Root . '/vendor/autoload.php';

        if (empty($Opts)) {
//F::Log('Empty CLI parameters', LOG_INFO);
        } else {
            if (isset($Opts['Service'])) {
                // log is not available before bootstrap
                fwrite(STDERR, "Service: \033[1;32m" . $Opts['Service'] . "\033[0m" . PHP_EOL);
                if (isset($Opts['Method'])) {
                } else {
                    $Opts['Method'] = 'Do';
                }

                fwrite(STDERR, "Method: \033[1;32m" . $Opts['Method'] . "\033[0m" . PHP_EOL);

                try {
                    $Call = F::Bootstrap(
                        [
                            'Paths' => [
                                Root . DS . 'src',
                                Root . DS . 'vendor'
                            ],
                            'Environment' => $Opts['Environment'] ?? null,
                            'Service' => 'System.Interface.CLI',
                            'Method' => 'Do',
                            'Call' => $Opts
                        ]
                    );

                    F::Shutdown();
                } catch (Exception $e) {
                    F::Log('CLI Exception: ' . $e->getMessage(), LOG_CRIT, 'Developer');
                }
            }

            if (isset($Call['Return Code'])) {
                exit($Call['Return Code']);
            } else {
                exit(0);
            }
        }
    }
