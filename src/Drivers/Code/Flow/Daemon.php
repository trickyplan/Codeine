<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    // 10xs for http://leonid.shevtsov.me/ru/mnogoprocessovye-demony-na-php#ixzz23J4hMu6y

    self::setFn('Run', function ($Call)
    {
        declare(ticks = 1);

        $SH = function ($Signal) {return F::Run('Code.Flow.Daemon', 'Signal', ['Signal' => $Signal]);};

        for ($SigID = 1; $SigID <= 28; $SigID ++)
            pcntl_signal($SigID, $SH);

        $PIDFile = F::Run(null, 'PIDFile', $Call);

        if (F::Run(null, 'Running?', $Call))
        {
            echo 'Daemon already active';
            exit;
        }
        else
            file_put_contents($PIDFile, getmypid());

        $Ungrateful = [];

        while (F::getLive())
        {
            if ((count($Ungrateful) < $Call['MaxChilds']))
            {
                if (($Trigger = F::Live($Call['Trigger'])) !== null)
                {
                    $PID = pcntl_fork();

                    if ($PID == -1)
                        return -1; //TODO: ошибка - не смогли создать процесс
                    elseif ($PID)
                    {
                        $Ungrateful[$PID] = true;
                        echo 'Forked '.$PID.PHP_EOL;
                    }
                    else
                    {
                        echo getmypid().': '.F::Live($Call['Execute'], ['Run' => $Trigger[0]]).PHP_EOL;
                        exit;
                    }
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
                    echo 'Dead '.$Signaled.PHP_EOL;
                }
            }

            sleep(1);
        }

        unlink($PIDFile);

        return $Call;
    });

    self::setFn('Running?', function ($Call)
    {
        $PIDFile = F::Run(null, 'PIDFile', $Call);

        if (is_file($PIDFile) )
        {
            //проверяем на наличие процесса
            if (posix_kill(file_get_contents($PIDFile) ,0))
                return true; //демон уже запущен
            else //pid-файл есть, но процесса нет
                if (!unlink($PIDFile))
                    return (-1); //не могу уничтожить pid-файл. ошибка
        }

        return false;
    });

    self::setFn('PIDFile', function ($Call)
    {
        return $Call['PID']['Prefix'].$Call['DaemonID'].$Call['PID']['Postfix'];
    });

    self::setFn('Stop', function ($Call)
    {
        return F::setLive(false);
    });

    self::setFn('Signal', function ($Call)
    {
        if (isset($Call['Codes'][$Call['Signal']]))
        {
            $Signal = $Call['Codes'][$Call['Signal']];

//            echo $Signal.' caughted'.PHP_EOL;

            return F::Live($Call['Signals'][$Signal]);
            // или Нет обработчиков
        }
        // или неизвестный код

        return null;
    });

    self::setFn('Flush', function ($Call)
    {
        F::Reload();
        echo 'Core flushed'.PHP_EOL;
        return true;
    });