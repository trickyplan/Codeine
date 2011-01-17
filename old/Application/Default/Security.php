<?php
/**
 * @package Plugins
 * @name Security
 * @author BreathLess
 * @version 5.0
 * @copyright BreathLess, 2010
 */

self::$Object = new Object(self::$Name, self::$ID);

if (self::$Object->Get('Owner') != Client::$UID)
        throw new WTF('Access Denied', 403);

if (self::$Object->Get('Type') != 'Native')
        View::Nest('Auth/OnlyNative');

switch (Application::$Mode)
    {
        case 'Add':
                if (Server::$REST == 'get')
                    {
                        View::Nest('Application/_Shared/Security/Add');
                        View::Nest('Auth/Add/'.self::$Aspect);
                    }
                elseif (Server::$REST == 'post')
                    {
                        self::$Object->Add('Authorizer:Installed', Application::$Aspect);
                        self::$Object->Add('Authorizer:'.Application::$Aspect, Code::E('Security/Authorizers','Input',Server::Arg(Application::$Aspect), Application::$Aspect));
                        self::$Object->Save();
                        Event::Queue (array('Priority'=>128, 'Signal'=>'SecurityChanged'));
                    }
        break;

        case 'Del':
                self::$Object->Del('Authorizer:'.Server::Arg('Key'), Server::Arg('Value'));
                self::$Object->Save();
                Event::Queue (array('Priority'=>128, 'Signal'=>'SecurityChanged'));
        break;

    }

$Buffer = '';

$Authorizers = self::$Object->Get('Authorizer:Installed', false);

foreach ($Authorizers as $Authorizer)
    if (is_array($Keys = self::$Object->Get('Authorizer:'.$Authorizer, false)))
        foreach ($Keys as $Key)
            $Buffer.= View::Replace('Auth/Show', array('<name/>'=>$Authorizer, '<value/>'=>$Key));

View::Add($Buffer);