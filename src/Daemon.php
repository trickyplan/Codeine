<?php

    /**
     * @author bergstein@trickyplan.com
     * @time 5:17
     */

    // 10xs for http://leonid.shevtsov.me/ru/mnogoprocessovye-demony-na-php#ixzz23J4hMu6y

    require 'Core.php';
    require 'Codeine/vendor/autoload.php';

    $Opts = [];

    foreach ($argv as $arg)
    {
        if (preg_match('/--(\S+)\=(\S+)/', $arg, $Pockets))
            $Opts = F::Dot($Opts, $Pockets[1], $Pockets[2]);
        elseif (preg_match('/--(\S+)/', $arg, $Pockets))
            $Opts = F::Dot($Opts, $Pockets[1], true);
    }

    if (isset($Opts['no-daemonize']) or getenv('Environment') == 'Development')
        ;
    else
    {
        $ChildPID = pcntl_fork();
        if ($ChildPID)
          exit;
    }

    posix_setsid();
    
    if (file_exists('/etc/default/codeine'))
        $Opts['Path'] = file_get_contents('/etc/default/codeine');

    if (isset($Opts['Path']))
        define('Root', $Opts['Path']);
    else
        !defined('Root')? define('Root', getcwd()): false;

    include Root.'/vendor/autoload.php';
    
    $Call = F::Bootstrap
    ([
        'Paths' => [Root],
        'Service' => 'System.Interface.CLI',
        'Method' => 'Do',
        'Call' =>
        [
            'Service' => 'Code.Flow.Daemon',
            'Method'  => 'Run',
            'Execute' => $Opts
        ]
    ]);
    F::Log('Root folder: '.Root, LOG_INFO);

    F::Shutdown($Call);

    exit(0); //$Call['Return Code']);