<?php

    class Client implements IClient
    {
        public static $UID;     // Идентификатор клиента
        public static $Ticket;  // Уровень сессии
        public static $User;    // Уровень пользователя
        public static $Face;    // Уровень личности

        public static $Agent;
        public static $TrustIP = false;

        public static $Level = 0; // Эту применяем.
        /*
         * 0 - Сессия, гостевой вход
         * 1 - Пользователь, авторизован
         * 2 - Личность, авторизовался и выбрал лицо
         */

        public static $Language = 'ru_RU';
        public static $Data;

        public static function Initialize()
        {
            Code::Hook('Core', __CLASS__, 'beforeInitialize');

            self::$Ticket = new Object('Ticket');

            return true;

            if (!self::Audit())
                self::Register();

            if (self::$Ticket->Get('User') !== null)
            {
                self::$User = new Object('User');
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
                    }
                }

                self::$Authorized = true;
            }
            else
                self::$UID = (string) self::$Ticket;

            Code::Hook('Core', __CLASS__, 'afterInitialize');
        }

        public static function Shutdown()
        {
            if (!Core::$Crash)
            {
                self::$Ticket->Save();
                if (Client::$Level > 0)
                {
                    if (Client::$Level == 2)
                        self::$Face->Save();
                    self::$Agent->Save();
                }
            }

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
            self::$Ticket->Add('UA', Server::Arg('HTTP_USER_AGENT'));

            Data::Create('Cookie', '{"I":"TID", "V":"'.$TID.'", "TTL":"159870000"}');
            Data::Create('Cookie', '{"I":"TSL", "V":"'.$TSL.'", "TTL":"159870000"}');

            return Log::Good('Ticket registered');
        }

        public static function Attach ($Username)
        {
            self::$User = new Object('User',$Username);
            self::$Ticket->Set('User', $Username);
            self::$Ticket->Set('Owner', (string)self::$User);
            self::$Ticket->Save();
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
            if (Server::Arg('TID') === null)
                return Log::Error('Client Ticket Not Defined.');

            if (Server::Arg('TSL') === null)
                return Log::Error('Client Seal Not Defined.');

            self::$Ticket = new Object('_Ticket');

            if (self::$Ticket->Load(Server::Arg('TID')) == false)
                return Log::Error('Ticket Not Exist.');

            $ClientSeal = Server::Arg('TSL');

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
        }

        public static function Annulate () {
            // TODO: Implement Annulate() method.
        }}