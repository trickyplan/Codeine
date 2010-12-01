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
                throw new WTF('Unknown class '.$Core);

            if ((include($Class)) === false)
                throw new WTF($Core.' not found');

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

                if (isset($_SERVER['HTTP_HOST']))
                {
                    define ('Host', $_SERVER['HTTP_HOST']);
                    define ('_Host', 'http://'.$_SERVER['HTTP_HOST'].'/');
                }
                else
                {
                    define ('Host', null);
                    define ('_Host', null);
                }

                self::$_Conf = self::_Configure(__CLASS__);

                spl_autoload_register ('Core::Load');

                Profiler::Initialize();
                return true;
            }
            catch (Exception $e)
            {
                $e->Panic();
            }
        }

        public static function Any($Variable)
        {
            if (Code::isValidCall($Variable))
                return Code::Run($Variable);
            elseif (Data::isValidCall($Variable))
                return Data::Read($Variable);
            else
                return $Variable;
        }
    }

    Core::Initialize();