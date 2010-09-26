<?php

    function F_TrustIP_Hook($IP = null)
    {
        $IPs = Core::$Conf['Options']['TrustHost'];

        if (!is_array($IPs))
            $IPs = array($IPs);

        foreach ($IPs as &$IP)
            $IP = gethostbyname($IP);

        if (in_array(_IP, $IPs))
            Client::$TrustIP = true;

        return true;
    }