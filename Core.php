<?php

define ('OBJSEP', '::');
define ('DIRSEP', '/');

if (isset($_SERVER['SERVER_NAME']))
    define ('_SERVER',$_SERVER['SERVER_NAME']);
else
    define ('_SERVER',$_SERVER['HTTP_HOST']);

define ('Engine', dirname(__FILE__).'/');
define ('EngineShared', dirname(__FILE__).'/_Shared/');
define ('Host', 'http://'.$_SERVER['HTTP_HOST'].'/');
define ('_Host', $_SERVER['HTTP_HOST'].'/');

if (isset($_SERVER['HTTP_X_REAL_IP']))
    define ('_IP', $_SERVER['HTTP_X_REAL_IP']);
else
    define ('_IP', $_SERVER['REMOTE_ADDR']);

include Engine.'/Package/krumo/class.krumo.php';

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
    public static $Engines = array();
    public static $StartTime;
    public static $Conf  = array();
    public static $Crash = false;
    
    public static function Initialize()
    {
        self::$StartTime = microtime(true);
        
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
                if (file_exists(Root.'Sites/'._SERVER.'.json'))
                {
                    if (($EngineConf = json_decode(file_get_contents(Engine.'/Codeine.json'), true))==null)
                        throw new WTF('Engine configuration malformed.', 4049);

                    if (($SiteConf = json_decode(file_get_contents(Root.'Sites/'._SERVER.'.json'), true))==null)
                        throw new WTF('Site configuration malformed.', 4049);

                    self::$Conf = ConfWalk ($EngineConf, $SiteConf);
                }
                else
                    throw new WTF('Not found '._SERVER.'.conf', 4049);

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
            return include (Engine.'Core/'.$Core.'/'.self::$Conf['Engines'][$Core].'.php');
        else
            return Log::Error('Unknown kernel module');
    }
}

Core::Initialize();