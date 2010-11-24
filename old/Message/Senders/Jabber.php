<?php

include_once Engine.'Code/Class/XMPPHP/XMPPHP/XMPP.php';

function F_Jabber_Mount($Args)
        {
            list ($Client, $Server) = explode('@',$Args['DSN']);
            list ($Host, $Port) = explode(':', $Server);
            list ($User, $Password) = explode(':', $Client);
            $conn = new XMPPHP_XMPP($Host, $Port, $User, $Password, 'OX!', $Host, $printlog=false, $loglevel=XMPPHP_Log::LEVEL_INFO);
            $conn->connect();
            $conn->processUntil('session_start');
            $conn->presence();
            return $conn;
        }

function F_Jabber_Unmount($Args)        
        {
            return $Args['Point']->disconnect();
        }

function F_Jabber_Send($Args)
	{
            return $Args['Point']->message($Args['To'], $Args['From']->Name().' > '.$Args['Subject'].":".$Args['Message']);
	}

