<?php

    include 'Component.php';

    class Core extends Component
    {
        protected static $_Conf;
        
        public static function Load ($Core)
        {
            if (substr($Core, 0,1) == 'I')
                $Class = Engine.'/Interface/'.substr($Core,1).'.php';
            elseif (isset(self::$_Conf['Engines'][$Core]))
                $Class = Engine.'Core/'.$Core.'/'.self::$_Conf['Engines'][$Core].'.php';
            else
                throw new Exception('Unknown class '.$Core);

            if ((include($Class)) === false)
                throw new Exception($Core.' not found');

            if (substr($Core, 0,1) !== 'I')
            {
                call_user_func(array($Core, 'Initialize'));
                register_shutdown_function($Core.'::Shutdown');
            }

            return true;
        }

        public static function Initialize()
        {           
            try
            {
                define ('DS', DIRECTORY_SEPARATOR);
                define ('Engine', __DIR__.DS);

                defined('Environment') || define('Environment',
                    (getenv('Codeine_Enviroment') ? getenv('Codeine_Enviroment') : 'Production'));

                define ('Host', $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST']: 'localhost');
                define ('_Host', 'http://'.Host);

                self::$_Conf = self::_Configure(__CLASS__);

                spl_autoload_register ('Core::Load');

                Profiler::Initialize();
                return true;
            }
            catch (Exception $e)
            {
                die($e->getMessage());
            }
        }

        public static function Any($Variable, $Routing = false)
        {
            if ($Routing || Code::isValidCall($Variable))
            {
                if (($Result = Code::Run($Variable)) !== null)
                    return $Result;
            }

            if ($Routing || Data::isValidCall($Variable))
            {
                if (($Result = Data::Read($Variable)) !== null)
                    return $Result;
            }

            if ($Routing || Message::isValidCall($Variable))
            {
                if (($Result = Message::Send($Variable)) !== null)
                    return $Result;
            }

            return $Variable;
        }
    }

    Core::Initialize();