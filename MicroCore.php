<?php

    class WTF extends Exception
    {
        function Log()
        {
            Log::Error($this->getMessage());
        }

        function Panic()
        {
            Core::$Crash = true;
            die ('<div style="text-align: center;padding: 4px; color: #FFF; background-color: #900;">Kernel panic:  '.$this->getMessage().'</div>');
        }
    }

    class Core
    {
        public static $StartTime;
        public static $Conf  = array();
        public static $Crash = false;

        private static function _Server ()
        {
            if (isset($_SERVER['SERVER_NAME']))
                return $_SERVER['SERVER_NAME'];
            else
            {
                if (isset($_SERVER['HTTP_HOST']))
                    return $_SERVER['HTTP_HOST'];
                else
                    return 'localhost';
            }
        }

        private static function _IP ()
        {
            if (isset($_SERVER['HTTP_X_REAL_IP']))
                return $_SERVER['HTTP_X_REAL_IP'];
            elseif (isset($_SERVER['REMOTE_ADDR']))
                return $_SERVER['REMOTE_ADDR'];
            else
                return '127.0.0.1';
        }

        public static function Load ($Core)
        {
            if (isset(self::$Conf['Engines'][$Core]))
            {
                if (file_exists(Engine.'Core/'.$Core.'/'.self::$Conf['Engines'][$Core].'.php'))
                    return include (Engine.'Core/'.$Core.'/'.self::$Conf['Engines'][$Core].'.php');
                else
                    return Log::Error('Kernel module not found');
            }
            else
                return Log::Error('Unknown kernel module');
        }

        private static function _ConfWalk ($Engine, $Site = array())
            {
                if (is_array($Site))
                    foreach ($Site as $Key => $Value)
                    {
                        if (!isset($Engine[$Key]))
                            $Engine[$Key] = array();

                        if (is_array($Value))
                            $Engine[$Key] = self::_ConfWalk($Engine[$Key],$Value);
                        else
                            $Engine[$Key] = $Value;
                    }
                else
                    $Site = $Engine;

                return $Engine;
            }

        public static function LoadConf()
        {
            if (file_exists(Root.'Conf/'._Host.'.json'))
                    {
                        if (($EngineConf = json_decode(file_get_contents(Engine.'Conf/Codeine.json'), true))==null)
                            throw new WTF('Engine configuration malformed. <a href="http://jsonlint.com/">Check JSON syntax</a>', 4049);

                        if (($SiteConf = json_decode(file_get_contents(Root.'Conf/'._Host.'.json'), true))==null)
                            throw new WTF('Site configuration malformed. <a href="http://jsonlint.com/">Check JSON syntax</a>', 4049);

                        self::$Conf = self::_ConfWalk ($EngineConf, $SiteConf);
                    }
                    else
                        throw new WTF('Not found '.Root.'Conf/'._Host.'.json', 4049);
           return true;
        }

        public static function Initialize()
        {
            self::$StartTime = microtime(true);

            define ('_Host', $_SERVER['HTTP_HOST']);
            define ('Host', 'http://'._Host.'/');

            define ('_SERVER', self::_Server());
            define ('_IP',     self::_IP());

            define ('OBJSEP', '::');
            define ('DS', DIRECTORY_SEPARATOR);

            define ('Engine', dirname(__FILE__).'/');
            define ('EngineShared', dirname(__FILE__).'/_Shared/');

            define ('Domain', implode('.',array_reverse(explode('.',_Host.'.'))));
            try
                {
                    self::LoadConf();

                    if (isset(self::$Conf['Options']['Maintenance']) && self::$Conf['Options']['Maintenance'] == true)
                    {
                        readfile(Root.'Layout/Site/Maintenance.html');
                        exit();
                    }

                    spl_autoload_register ('Core::Load');

                    Profiler::Initialize ();

                    Profiler::Go ('Core');

                        Server::Initialize();
                        Client::Initialize();

                    Profiler::Stop ('Core');

                }

            catch (WTF $e)
                {
                    $e->Panic();
                }
        }
    }

    Core::Initialize();