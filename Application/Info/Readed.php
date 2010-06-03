<?php

    $Object = new Object(self::$Name);

    if ($Object->Load(self::$ID))
    {
        Client::$User->Add('Info:Readed', self::$ID);
        Client::$User->Save();
    }

    Page::Body('');