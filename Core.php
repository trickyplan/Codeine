<?php

class WTF extends Exception
{
    function Log()
    {
        Log::Error($this->getMessage());
    }

    function Panic()
    {
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

    private static function _Workstyle()
    {
        if (!($env = getenv('CodeineWorkstyle')))
            $env = 'production';
        
        return $env;
    }

    public static function Initialize()
    {
        self::$StartTime = microtime(true);
       
        define ('_SERVER', self::_Server());
        define ('_IP',     self::_IP());
        define ('_WS',     self::_Workstyle());
        
        define ('OBJSEP', '::');
        define ('DS', DIRECTORY_SEPARATOR);

        define ('Engine', dirname(__FILE__).'/');
        define ('EngineShared', dirname(__FILE__).'/_Shared/');

        define ('_Host', $_SERVER['HTTP_HOST']);
        define ('Host', 'http://'._Host.'/');
        
        include Engine.'/Package/krumo/class.krumo.php';

        function ConfWalk ($Engine, $Site = array())
        {
            if (is_array($Site))
                foreach ($Site as $Key => $Value)
                {
                    if (!isset($Engine[$Key]))
                        $Engine[$Key] = array();

                    if (is_array($Value))
                        $Engine[$Key] = ConfWalk($Engine[$Key],$Value);
                    else
                        $Engine[$Key] = $Value;
                }
            else
                $Site = $Engine;

            return $Engine;
        }

        try
            {
                if (file_exists(Root.'etc/'._WS.'.json'))
                {
                    if (($EngineConf = json_decode(file_get_contents(Engine.'etc/'._WS.'.json'), true))==null)
                        throw new WTF('Engine configuration malformed. <a href="http://jsonlint.com/">Check JSON syntax</a>', 4049);

                    if (($SiteConf = json_decode(file_get_contents(Root.'etc/'._WS.'.json'), true))==null)
                        throw new WTF('Site configuration malformed. <a href="http://jsonlint.com/">Check JSON syntax</a>', 4049);

                    self::$Conf = ConfWalk ($EngineConf, $SiteConf);
                }
                else
                    throw new WTF('Not found '.Root.'etc/'._WS.'.json', 4049);

                spl_autoload_register ('Core::Load');

                Timing::Initialize ();

                Log::Info ('Started');

                Timing::Go ('Core');

                    Server::Initialize();
                    Client::Initialize();

                Timing::Stop ('Core');

            }

        catch (WTF $e)
            {
                $e->Panic();
            }
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
}

Core::Initialize();