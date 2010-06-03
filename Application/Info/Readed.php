<?php

    $Object = new Object(self::$Name);
    $Object->Load(self::$ID);
    
    Client::$User->Add('Info:Readed', self::$ID);

    if (Client::$User->Save())
        Page::Body('OK');
    else
        Page::Body('Fail');

    