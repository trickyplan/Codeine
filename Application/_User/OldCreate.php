<?php

switch (Server::$REST)
{

    case 'post':
        $Object = new Object(self::$Name, Server::Arg('Login'));
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
                    Client::Redirect('/_User/~'.$Object->Name);
                }
        else
            Page::Body(json_encode($Errors));

    break;
}