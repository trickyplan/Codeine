<?php

    $Hook = function ()
    {
        $_REQUEST = array_merge($_ENV, $_REQUEST, $_COOKIE, $_POST, $_GET);
        $VAR = $_REQUEST;

        Server::Data(Code::E('Input/Filters','Filter', $VAR));

        if (isset($_FILES))
            foreach($_FILES as $Key => $Value)
                Server::Arg($Key, $Value['tmp_name']);

        foreach($_SERVER as $Key => $Value)
            Server::Arg($Key, $Value);

        return true;
    };