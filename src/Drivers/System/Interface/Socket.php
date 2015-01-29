<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        ini_set('implicit_flush', true);

        $Address = 'localhost'; //Адрес работы сервера

        if (($Socket = socket_create(AF_INET, SOCK_STREAM, 0)) < 0)
        {
            //AF_INET - семейство протоколов
            //SOCK_STREAM - тип сокета
            //SOL_TCP - протокол
            F::Log('Error creating socket', LOG_ERR);
        }
        else
            F::Log('Socket created', LOG_INFO);

        if (($ret = socket_bind($Socket, $Address, $Call['Port'])) < 0)
            F::Log('Error binding socket', LOG_ERR);
        else
            F::Log('Socket binded to '.$Address.':'.$Call['Port'], LOG_INFO);

        //Начинаем прослушивание сокета (максимум 5 одновременных соединений)
        if (($Return = socket_listen($Socket, 1)) < 0)
            F::Log('Error listening socket', LOG_ERR);
        else
            F::Log('Socket start listening', LOG_INFO);

        do
        {
            //Принимаем соединение с сокетом
            if (($Message = socket_accept($Socket)) < 0)
                F::Log('Error accepting connection', LOG_ERR);
            else
            {
                F::Log('Socket ready to connect', LOG_INFO);

                $Request = '';
                $Output = 'HTTP/1.1 200 OK';

                do
                {
                    if (false === ($Buffer = socket_read($Message, 1024)))
                        F::Log('Error reading socket', LOG_ERR);
                    else
                    {
                        $Request.= $Buffer;

                        if (empty(trim($Buffer)))
                        {
                            F::Log('Message received', LOG_INFO);
                            $Headers = http_parse_headers($Request);
                            print_r($Headers);
                            print_r($Request);

        /*                    $Call['UA'] = $Headers['User-Agent'];
                            $Call['HTTP']['URL'] = $Headers['Request Url'];*/

        /*                    $Call = F::Run('Code.Flow.Front', 'Run', $Call);*/

                            socket_write($Socket, $Output.chr(0), 64);
                            break;
                        }
                    }
                }
                while (true);
            }

        } while (true);

        if (isset($Scoket))
        {
            socket_close($Socket);
            F::Log('Socket closed', LOG_INFO);
        }

        return $Call;
    });