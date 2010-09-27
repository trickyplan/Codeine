<?php

if (!self::$Object->Load(self::$ID))
    throw new WTF('4040',404);

if (!self::$Object->Get('Owner') == Client::$UID or !Access::Check(self::$Object, self::$Plugin))
    throw new WTF ('Access Denied', 4030);

switch (Server::$REST)
{
    case 'post':
        if (self::$Object->Update(Server::Data()))
        {
            switch (self::$Interface)
                {
                    case 'ajax':
                        Page::Body('Success');
                    break;

                    default:
                        Client::Redirect(Host.self::$Name.'/~'.self::$ID);
                    break;
                }
        }
        else
            Page::Body('Failed');
    break;
}