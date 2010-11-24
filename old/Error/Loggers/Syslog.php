<?php

    function F_Syslog_Initialize($Args)
    {
        return openlog(_SERVER, LOG_PID | LOG_PERROR, LOG_LOCAL0);
    }

    function F_Syslog_Info($Args)
    {
        return syslog(LOG_INFO, $Args['Message']);
    }

    function F_Syslog_Error($Args)
    {
        return syslog(LOG_ERR, $Args['Message']);
    }

    function F_Syslog_Warning($Args)
    {
        return syslog(LOG_WARNING, $Args['Message']);
    }

    function F_Syslog_Bad($Args)
    {
        return syslog(LOG_WARNING, $Args['Message']);
    }

    function F_Syslog_Good ($Args)
    {
        return syslog(LOG_INFO, $Args['Message']);
    }

    function F_Syslog_Dump($Args)
    {
        return syslog(LOG_INFO, $Args['Message']);
    }

    function F_Syslog_Important($Args)
    {
        return syslog(LOG_ERR, $Args['Message']);
    }

    function F_Syslog_Stage($Args)
    {
        return syslog(LOG_ERR, '['.$Args['Message'].']');
    }

    function F_Syslog_Hint($Args)
    {
        return syslog(LOG_INFO, $Args['Message']);
    }

    function F_Syslog_Perfomance($Args)
    {
        return syslog(LOG_INFO, $Args['Message']);
    }

    function F_Syslog_Shutdown($Logger)
    {
        return closelog();
    }