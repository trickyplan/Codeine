<?php

switch (Server::$REST)
{
    case 'get':

    break;
    
    case 'post':
        self::$Object = new Object(self::$Name);
        
        $Errors = self::$Object->Create(Server::Data());
    
        if ($Errors === true)
            {
                Event::Queue (
                        array('Priority' => 64,
                              'Signal' => self::$Name.'Created',
                              'Subject'=> (string) self::$Object));

                if (Client::$Face == 2)
                    Client::$Face->Inc('Stats:'.self::$Name.':Total',1);
                
                switch (self::$Interface)
                {
                    case 'ajax':
                        View::Body('Success');
                    break;
                
                    default:
                        Client::Redirect(Host.self::$Name.'/~'.self::$Object->Name);
                    break;
                }
            }
        else
            View::Body(json_encode($Errors));
        
    break;
}