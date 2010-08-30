<?php

    function F_Syslog_Output($Messages)
    {
        openlog(_SERVER, LOG_PID | LOG_PERROR, LOG_LOCAL0);
        
        foreach($Messages as $AppID => $AppMessages)
            foreach($AppMessages as $Message)
                switch($Message[1])
                {
                    case 0:   syslog(LOG_INFO,    $Message[2].':'.$Message[0]); break;
                    case 1:   syslog(LOG_ERR,     $Message[2].':'.$Message[0]); break;
                    case 2:   syslog(LOG_WARNING, $Message[2].':'.$Message[0]); break;
                    case 3:   syslog(LOG_WARNING, $Message[2].':'.$Message[0]); break;
                    case 4:   syslog(LOG_INFO,    $Message[2].':'.$Message[0]); break;
                    case 5:   syslog(LOG_INFO,    $Message[2].':'.$Message[0]); break;
                    case 6:   syslog(LOG_INFO,    $Message[2].':'.$Message[0]); break;
                    case 7:   syslog(LOG_INFO,    $Message[2].':'.$Message[0]); break;
                    case 8:   syslog(LOG_INFO,    $Message[2].':'.$Message[0]); break;
                    default:  syslog(LOG_INFO,    $Message[2].':'.$Message[0]); break;
                }
        
        closelog();
       
        return null;
    }
