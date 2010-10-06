<?php

    class Application
    {
        public static $Routers   = array ('Native');
        public static $Call      = null;
        public static $Interface = 'web';
        public static $Name      = null;
        public static $Plugin    = null;
        public static $ID        = null ;
        public static $Mode      = null;
        public static $In;
        public static $Aspect    = null;
        public static $Object;
        public static $Collection;

        private static $_AppID     = 'Core';
        private static $AppObject;
        private static $_Plugins = array();
        private static $Legacy = array ('Interface', 'Name', 'Plugin', 'ID', 'Mode', 'Aspect');

        public static function Initialize()
        {
            return self::$Routers = Core::$Conf['Drivers']['Installed']['Routers'];
        }

        public static function AppID ()
        {
            return self::$_AppID;
        }

        public static function Route ($Call)
        {
            Profiler::Go('Routing');

            if ($Call === '/' or null === $Call)
                $Call = Core::$Conf['Options']['Start'];

            $Routed = null;

            self::$In = array();

            foreach(self::$Legacy as $cLegacy)
                self::$$cLegacy = null;

            self::$Call = $Call;

            if (mb_strpos($Call,'?') !== false)
                list(self::$Call, $Query) = explode ('?', $Call);

            foreach (self::$Routers as $Router)
                if (($Routed = Code::E('Routers','Route', self::$Call, $Router)) !== null)
                     break;

            if ($Routed == null)
                throw new WTF("\n".'Error routed.', 4049);

            foreach ($Routed as &$Value)
                if (is_string($Value))
                    $Value = urldecode($Value);

            self::$In = $Routed;

            if (isset($Query))
            {
                mb_parse_str($Query, $Result);
                self::$In = array_merge(self::$In, $Result);
            }

            foreach(self::$Legacy as $cLegacy)
                if (isset(self::$In[$cLegacy]))
                    self::$$cLegacy = self::$In[$cLegacy];

            self::$_AppID = Client::$UID.'@'.json_encode(self::$In);

            Profiler::Stop('Routing');
            return true;
        }

        public static function Run ($Sliced = false)
        {
            Profiler::Go('Exec: '.self::$Call);

            Code::Hook('Core', __CLASS__, 'beforeRun');
            Code::Hook('Plugin', self::$Plugin, 'beforeRun');
            Code::Hook('Application', self::$Name, 'beforeRun');

            Log::Stage(self::$Call);

            try
            {
                self::$AppObject = new Object ('_Application');

                View::Start(self::$_AppID);

                if (!self::$AppObject->Load(self::$Name))
                        throw new WTF('Unknown Application '.self::$Name.':'.self::$Plugin, 4041);

                if (null !== self::$ID)
                    {
                        self::$Object = new Object(self::$Name);
                        self::$Collection = new Collection(self::$Name);
                    }

                $PluginType = self::$AppObject->GetKeyOf(self::$Plugin);

                if (!self::$Plugin)
                    self::$Plugin = self::$AppObject->Get('Plugin:Default');

                View::Nest('Application/'.self::$Name.'/'.self::$Name);
                View::Nest(array ('Application/'.self::$Name.'/'.self::$Plugin, 'Application/Default/'.self::$Plugin));

                switch ($PluginType)
                {
                    case 'Plugin:Local:Own':
                        $PluginPath = Root.Server::Path('Apps').self::$Name;
                        $PluginScope = self::$Name;
                    break;

                    case 'Plugin:Local:Shared':
                        $PluginPath = Root.Server::Path('Apps').'Default';
                        $PluginScope = 'Default';
                    break;

                    case 'Plugin:System:Own':
                        $PluginPath = Engine.Server::Path('Apps').self::$Name;
                        $PluginScope = self::$Name;
                    break;

                    case 'Plugin:System:Shared':
                        $PluginPath = Engine.Server::Path('Apps').'Default';
                        $PluginScope = 'Default';
                    break;

                    default:
                        $PluginScope = 'Direct';
                    break;
                }

                if (Core::$Conf['Options']['AllowDirectCall'] and !isset($PluginPath))
                {
                    if (file_exists(Root.Server::Path('Apps').self::$Name.'/'.self::$Plugin.'.php'))
                        $PluginPath = Root.Server::Path('Apps').self::$Name;
                    elseif (file_exists(Engine.Server::Path('Apps').self::$Name.'/'.self::$Plugin.'.php'))
                        $PluginPath = Engine.Server::Path('Apps').self::$Name;
                    else
                        throw new WTF('Unknown Direct Call Plugin '.self::$Name.':'.self::$Plugin, 4042);
                }

                if (!isset(self::$_Plugins[$PluginScope][self::$Name]))
                {
                    if (file_exists($PluginPath.'/'.self::$Plugin.'.'.self::$Interface.'.php'))
                        $PluginFile = $PluginPath.'/'.self::$Plugin.'.'.self::$Interface.'.php';
                    elseif (file_exists($PluginPath.'/'.self::$Plugin.'.php'))
                        $PluginFile = $PluginPath.'/'.self::$Plugin.'.php';
                    else
                        throw new WTF('Unknown Plugin '.self::$Name.':'.self::$Plugin, 4042);

                    include $PluginFile;

                    self::$_Plugins[$PluginScope][self::$Name] = $F;
                }
                else
                    $F = self::$_Plugins[$PluginScope][self::$Name];

                $F(self::$In);
                

            }
            catch (Exception $E)
            {
                Profiler::Stop('Exec: '.self::$Call);
                View::Reset('Errors/Crash');
                View::Add($E->getCode(), 'code');
                View::Add($E->getMessage(), 'message');
            }

            self::$Mode = '';

            Code::Hook('Core', __CLASS__, 'afterRun');
            Code::Hook('Plugin', self::$Plugin, 'afterRun');
            Code::Hook('Application', self::$Name, 'afterRun');

            Profiler::Stop('Exec: '.self::$Call);

            return self::$_AppID;
        }


        public static function SetTune($Tune, $Value = null)
        {
            if (Client::$Level>0)
                return Client::$Agent->Set('Tune:'.self::$Name.':'.$Tune, $Value);
            else
                return $Value;
        }

        public static function GetTune($Tune, $Or = null)
        {
            $Application = self::$AppObject->Get('Tune:'.$Tune);

            if ($Application == null)
                $Application = $Or;

            if (Client::$Level>0)
            {
                $Agent   = Client::$Agent->GetOr(array('Tune:'.self::$Name.':'.$Tune));

                if (null !== $Agent)
                    return $Agent;
                elseif (null !== $Application)
                    return $Application;
            }
            else
                return $Application;
        }
    }
