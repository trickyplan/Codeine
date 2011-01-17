<?php

    function F_GeoIP_Hook($IP = null)
    {
        if (null === $IP)
            $IP = _IP;

        if (Client::$Level > 0)
        {
            if (Client::$Agent->Get('IP') == $IP)
                return true;

            Client::$Agent->Set('IP', $IP);
        }

        $IPTable = new Object('_IP2Geo');

        if ($IPTable->Load(ip2long($IP)) == false)
            {
                $IP2Geo = Code::E('Service/IP2Geo','Get', $IP);

                $IPTable->Set($IP2Geo);
                $IPTable->Save();
            }
        else
            $IP2Geo = $IPTable->Data();

        if (Client::$Level == 2)
            foreach($IP2Geo as $Key => $Value)
            {
                Client::$Face->Set('Geo:'.$Key, $Value);
                Client::$Ticket->Set('Geo:'.$Key, $Value);
                Client::$User->Set('Geo:'.$Key, $Value);
            }

        return true;
    }