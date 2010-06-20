<?php

switch (Server::$REST)
{
    case 'get':
        Form::Load (self::$Name);
        if (!self::$ID)
            self::$ID = 'Default';

        $DefaultObject = new Object(self::$Name, self::$ID);

        Page::Add(Form::Render('Form/Default', $DefaultObject->Data()));

    break;

    case 'post':
        $Object = new Object(self::$Name, Server::Get('Login'));
        $Data = Server::Data();
        Client::$Agent = new Object('_User',$Object->Name);

        $Errors = $Object->Check($Data);
        
        if ($Errors === true)
                {
                    $Object->Create($Data);
                    $Object->Set('Owner',self::$Name.OBJSEP.$Object->Name);
                    $Object->Add('Type','Native');
                    $Object->Save();
                    Client::Attach($Object->Name);
                    Page::Body('/_User/~'.$Object->Name);
                }
        else
            Page::Body(json_encode($Errors));

    break;
}