<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    // 10xs for http://leonid.shevtsov.me/ru/mnogoprocessovye-demony-na-php#ixzz23J4hMu6y

    setFn('Run', function ($Call)
    {
        if (!isset($Call['Daemons']))
            exit(1);
        else
        {
            foreach ($Call['Daemons'] as $DaemonName => $Daemon)
                F::Log('Daemon *'.$DaemonName.'* loaded with 1/'.$Daemon['Frequency'].' frequency', LOG_INFO);

            F::Log(count($Call['Daemons']).' daemons found');
        }

        declare(ticks = 1);

        $RT = $Call['RT'];

        $SH = function ($Signal) use ($Call)
        {
            return F::Run('Code.Flow.Daemon', 'Signal', $Call,
                [
                    'Signal' => $Signal
                ]);
        };

        $Call['Daemon']['Childs']['Max'] = F::Live($Call['Daemon']['Childs']['Max']);
       /* $PID = pcntl_fork();

        if ($PID == -1)
        {
            F::Log('Daemon: Detach failed', LOG_CRIT);
            exit(1);
        }
        else if ($PID)
            {
                F::Log('Daemon: Detach success', LOG_INFO);
                exit;
            }
        else*/
        {
            foreach ($Call['Signals'] as $SigID => $Hook)
                if (!pcntl_signal(constant($SigID), $SH))
                    F::Log('Signal '.$SigID.' not handled', LOG_ERR);
                else
                    F::Log('Signal '.$SigID.' handling', LOG_INFO);

            $PIDFile = F::Run(null, 'PIDFile', $Call);

            if (F::Run(null, 'Running?', $Call))
            {
                /*F::Log('Daemon already active: '.F::Run(null, 'PIDFile', $Call), LOG_CRIT);
                exit (2);*/
            }
            else
            {
                if (file_put_contents($PIDFile, getmypid()) != false)
                    F::Log('PID file created: '.$PIDFile, LOG_INFO);
                else
                    F::Log('PID file failed: '.$PIDFile, LOG_ERR);

            }

            $Ungrateful = [];

            F::Log('Daemon started', LOG_INFO);

            $Ticks = 0;

            while (F::Run(null, 'Running?', $Call))
            {
                $Ticks++;

                if ($Ticks == PHP_INT_MAX)
                    $Ticks = 0;

                F::Log('Tick '.$Ticks, LOG_DEBUG);

                if ((count($Ungrateful) < $Call['Daemon']['Childs']['Max']))
                {
                    $PID = pcntl_fork();

                    if ($PID == -1)
                    {
                        F::Log('Daemon: Fork failed', LOG_CRIT);
                        return -1;
                    } //TODO: ошибка - не смогли создать процесс
                    elseif ($PID)
                    {
                        $Ungrateful[$PID] = true;
                        F::Log('Child forked '.$PID, LOG_DEBUG);
                    }
                    else
                    {
                        foreach ($Call['Daemons'] as $DaemonName => $Daemon)
                            if (($Ticks % $Daemon['Frequency']) == 0)
                            {
                                F::Log($DaemonName.' daemon waked up', LOG_DEBUG);

                                $Result = F::Live($Daemon['Execute'], $Call);

                                if ($Result !== null)
                                    F::Log($DaemonName.'> '.getmypid().': '.j($Result), LOG_INFO);
                            }

                        exit(4);
                    }
                }

                while ($Signaled = pcntl_waitpid(-1, $Status, WNOHANG))
                {
                    if ($Signaled == -1)
                    {
                        $Ungrateful = [];
                        break;
                    }
                    else
                    {
                        unset($Ungrateful[$Signaled]);
                        F::Log('Child dead '.$Signaled, LOG_DEBUG);
                    }
                }

                usleep($RT);
            }

            F::Log('Daemon stopped', LOG_WARNING);
            if (file_exists($PIDFile))
                unlink($PIDFile);
        }

        return $Call;
    });

    setFn('Running?', function ($Call)
    {
        $PIDFile = F::Run(null, 'PIDFile', $Call);

        if (file_exists($PIDFile))
        {
            //проверяем на наличие процесса
            if (posix_kill((int) file_get_contents($PIDFile), 1))
                return true; //демон уже запущен
            else //pid-файл есть, но процесса нет
            {
                if (posix_get_last_error() == 1) /* EPERM */
                    return true;

                if (is_writable($PIDFile) && !unlink($PIDFile))
                    return (-1);
            } //не могу уничтожить pid-файл. ошибка
        }

        return false;
    });

    setFn('PIDFile', function ($Call)
    {
        return Root.DS.$Call['PID']['Prefix'].$Call['PID']['Name'].$Call['PID']['Postfix'];
    });

    setFn('Stop', function ($Call)
    {
        $PIDFile = F::Run(null, 'PIDFile', $Call);

        if (file_exists($PIDFile))
            unlink($PIDFile);

        return F::setLive(false);
    });

    setFn('Signal', function ($Call)
    {
        if ($Call['Signal'] > 1)
        {
            F::Log('Caught signal '.$Call['Signal'], LOG_INFO);

            if (isset($Call['Codes'][$Call['Signal']]))
            {
                $Signal = $Call['Codes'][$Call['Signal']];

                if (isset($Call['Signals'][$Signal]))
                    return F::Live($Call['Signals'][$Signal]);
                // или Нет обработчиков
            }
            // или неизвестный код
        }

        return null;
    });

    setFn('Flush', function ($Call)
    {
        return true;
    });