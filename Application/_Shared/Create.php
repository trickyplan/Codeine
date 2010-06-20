<?php

switch (Server::$REST)
{
    case 'get':
        
        Form::Load (self::$Name);

        if (self::$ID)
            self::$Object->Load(self::$ID);
        else
            self::$Object->Load('Default');

        Page::Add(Form::Render('Form/Default', self::$Object->Data()));

    break;
    
    case 'post':
    
        $Errors = self::$Object->Create(Server::Data());
    
        if ($Errors === true)
            {
                Event::Queue (array('Priority'=>64, 'Signal'=>self::$Name.'Created', 'Subject'=>(string)self::$Object));
                Client::$Face->Inc('Stats:'.self::$Name.':Total',1);
                switch (self::$Interface)
                {
                    case 'ajax':
                        Page::Body('Success');
                    break;
                
                    default:
                        Page::Body(Host.self::$Name.'/~'.self::$Object->Name);
                    break;
                }
            }
        else
            Page::Body(json_encode($Errors));
        
    break;
}