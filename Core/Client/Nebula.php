<?php

class Client
{
    public static $UID;     // Идентификатор клиента
    public static $Ticket;  // Уровень сессии
    public static $User;    // Уровень пользователя
    public static $Face;    // Уровень личности

    public static $Agent;
    public static $TrustIP = false;

    public static $Authorized = false; // Эту переменную в 4.20 уберём.
    public static $Level = 0; // Эту применяем.
    /*
     * 0 - Сессия, гостевой вход
     * 1 - Пользователь, авторизован
     * 2 - Личность, авторизовался и выбрал лицо
     */

    public  static $Language = 'ru_RU';
    public  static $Data;
    
    public static function GeoIP ($IP = null)
    {
        if (null === $IP)
            $IP = _IP;

        if (self::$Agent->Get('IP') == $IP)
            return true;

        self::$Agent->Set('IP', $IP);

        $IPTable = new Object('_IP2Geo');

        if ($IPTable->Load(ip2long($IP)) == false)
            {
                $IP2Geo = Code::E('Service/IP2Geo','Get', $IP);

                $IPTable->Set($IP2Geo);
                $IPTable->Save();
            }
        else
            $IP2Geo = $IPTable->Data();

        foreach($IP2Geo as $Key => $Value)
        {
            self::$Face->Set('Geo:'.$Key, $Value);
            self::$Ticket->Set('Geo:'.$Key, $Value);
            self::$User->Set('Geo:'.$Key, $Value);
        }

        return true;
    }
    
    public static function Initialize()
    {       
        self::$Level = 0;
        self::$Ticket = new Object('_Ticket');

        if (!self::Audit())
            self::Register();
       
        if (self::$Ticket->Get('User') !== null)
        {
            self::$User = new Object('_User');
            self::$User->Load(self::$Ticket->Get('User'));

            self::$Level = 1;
            self::$Agent = &self::$User;
            self::$UID = (string) self::$User;
           
            if (($FaceID = self::$User->Get('Face')) !== null)
            {
                self::$Face = new Object($FaceID);
                if (self::$Face->Load())
                {
                    self::$Level = 2;
                    self::$Agent = &self::$Face;
                    self::$UID = (string) self::$User;
                    self::GeoIP();
                }
            }

            if (null !== ($Language = self::$Agent->Get('Language')))
                self::$Language = $Language;
            else
                self::$Language = 'ru_RU';

            setlocale(LC_ALL, self::$Language.'.UTF-8');

            if (self::$Ticket->Get('LastHit')<(time()-60))
                self::$Ticket->Set('LastHit', time());

            if (self::$Agent->Get('LastHit')<(time()-60))
            {
                self::$Agent->Set('LastHit', time());
                if ((self::$Agent->Get('Online') == 'False') or (self::$Agent->Get('Online') === null))
                    self::$Agent->Set('Online', 'True');
            }
            
            self::$Authorized = true;
        }
        else
            self::$UID = (string) self::$Ticket;

        $IPs = Core::$Conf['Options']['TrustHost'];

        if (!is_array($IPs))
            $IPs = array($IPs);
        
        foreach ($IPs as &$IP)
            $IP = gethostbyname($IP);
            
        if (in_array(_IP, $IPs))
            Client::$TrustIP = true;
    }

    public static function Shutdown()
    {
        if (!Core::$Crash)
        {
            if (Client::$Authorized)
            {
                if (Client::$Level == 2)
                    self::$Face->Save();
                self::$Agent->Save();
            }

        }
        
        self::$Ticket->Save();

        return !Core::$Crash;
    }


    private static function _Seal()
    {
        return sha1(self::$Ticket->Name.'/');
    }

    public static function Register ()
    {
        $TID = uniqid('T',true).uniqid('I',true);

        self::$Ticket->Load($TID);

        $TSL = self::_Seal();
        // Порядок сохранить!
        self::$Ticket->Add('CreatedOn', time());
        self::$Ticket->Add('TSL', $TSL);
        self::$Ticket->Add('IP', _IP);
        self::$Ticket->Add('UA', Server::Get('HTTP_USER_AGENT'));

        Data::Create('Cookie', '{"I":"TID", "V":"'.$TID.'", "TTL":"159870000"}');
        Data::Create('Cookie', '{"I":"TSL", "V":"'.$TSL.'", "TTL":"159870000"}');

        return Log::Good('Ticket registered');
    }

    public static function Attach ($Username)
    {
        self::$User = new Object('_User',$Username);
        self::$Ticket->Set('User', $Username);
        self::$Ticket->Set('Owner', (string)self::$User);
        self::$Level = 1;
        
        return Log::Good('Ticket attached');
    }

    public static function Detach ()
    {
        self::$Ticket->Del('Type');
        self::$Ticket->Del('User');
        self::$Ticket->Del('MayBe');
        
        self::$Authorized = false;
        self::$Level = 0;
        
        return true;
    }

    public static function Audit ()
    {
        if (Server::Get('TID') === null)
            return Log::Error('Client Ticket Not Defined.');

        if (Server::Get('TSL') === null)
            return Log::Error('Client Seal Not Defined.');

        self::$Ticket = new Object('_Ticket');

        if (self::$Ticket->Load(Server::Get('TID')) == false)
            return Log::Error('Ticket Not Exist.');

        $ClientSeal = Server::Get('TSL');

        $ServerSeal = self::$Ticket->Get('TSL');
        $RealSeal   = self::_Seal();

        if ($ClientSeal != $RealSeal)
        {
            self::$Ticket->Erase();
            return Log::Error('Client Seal Not Valid');
        }

        if ($ClientSeal != $ServerSeal)
            return Log::Error('Client Seal '.$ClientSeal.' not equal Server Seal ');
        else
        {
            if ($Username = self::$Ticket->Get('User'))
                return Log::Good('Attached user: '.$Username);
            else
                return Log::Good('User not attached');
        }

        return false;
    }

    public static function Redirect($URL)
    {
        header('Location: '.$URL);
        die();
    }
}