<?php


    self::$Object = new Object();
    self::$Object->Load(self::$ID);
    
    if (self::$Object->Get('Owner') == (string) Client::$User or self::$Object->Get('Receiver') == (string) Client::$Face)
    {
        self::$Object->Erase();
        self::$Object->Save();
        Client::$Face->Inc('Stats:'.self::$Name.':Total',-1);
        Event::Queue (64, self::$Object->Scope.'Deleted', (string) self::$Object);
        Page::Body('Success');
    }
    else
        Page::Add('403');