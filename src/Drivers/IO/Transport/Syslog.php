<?php

    /* Codeine
     * @author BreathLess
     * @description: Syslog Transport
     * @package Codeine
     * @version 7.x
     * @date 29.07.21
     * @time 21:45
     */

    setFn('Open', function ($Call)
    {
        return openlog($_SERVER['HTTP_HOST'], LOG_PID | LOG_PERROR, LOG_LOCAL0);
    });

    setFn('Send', function ($Call)
    {
        if (!is_array($Call['Message']))
            $Call['Message'] = array($Call['Message']);

        if (isset($Call['Call']))
            $Aux = F::hashCall($Call['Call']);
        else
            $Aux = '';

        foreach ($Call['Message'] as $Ix => $Message)
            syslog(LOG_INFO, implode(' ',
                    array($Ix, $Message, $Aux)));

        return true;
    });

    setFn('Receive', function ($Call)
    {
        return $Call;
    });
