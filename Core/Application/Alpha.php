<?php

class Application
{
    public static $Routers   = array ('REST','Native', 'JSON', 'XML');
    // FIXME
    public static $Call      = null;
    public static $AppID     = null;
    public static $Interface = 'web';
    public static $Name      = null;
    public static $Plugin    = null;
    public static $ID        = null ;
    public static $Mode      = null;
    public static $In;
    public static $Aspect    = null;
    public static $Object;
    public static $Collection;
    
    private static $AppObject;
    private static $Legacy = array ('Interface', 'Name', 'Plugin', 'ID', 'Mode', 'Aspect');

    public static function Route ($Call)
    {
        Timing::Go('Routing');

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
            
            self::$AppID = Client::$UID.'@'.json_encode(self::$In);

        Timing::Stop('Routing');
        return true;
    }
    
    public static function Run ($Sliced = false)
    {
        Timing::Go('Exec: '.self::$AppID);

        Log::Stage(self::$AppID);

        try
        {
            self::$AppObject = new Object ('_Application');

            Page::Start(self::$AppID);

            if (!self::$AppObject->Load(self::$Name))
                    throw new WTF('Unknown Application '.self::$Name.':'.self::$Plugin, 4041);

            self::$Object = new Object(self::$Name);
            self::$Collection = new Collection(self::$Name);

            $Plugins = self::$AppObject->Get(
                    array(
                        'Plugin:Local:Own',
                        'Plugin:Local:Shared',
                        'Plugin:System:Own',
                        'Plugin:System:Shared',
                        'Plugin:Protected',
                        'Plugin:Heavy'), false);

            if (!self::$Plugin)
                self::$Plugin = self::$AppObject->Get('Plugin:Default');
            
            Page::$Slots['Title']['Application'] = '<l>Application:'.self::$Name.'</l>';
            Page::$Slots['Title']['Plugin'] ='<l>Plugin:'.self::$Plugin.'</l>';

            Page::Nest('Application/'.self::$Name.'/'.self::$Name);
            Page::Nest(array ('Application/'.self::$Name.'/'.self::$Plugin, 'Application/_Shared/'.self::$Plugin));

            if (is_array($Plugins['Plugin:Local:Own'])
                && in_array(self::$Plugin, $Plugins['Plugin:Local:Own']))
                    $PluginPath = Root.Apps.self::$Name;

            elseif (is_array($Plugins['Plugin:Local:Shared'])
                && in_array(self::$Plugin, $Plugins['Plugin:Local:Shared']))
                    $PluginPath = Root.Apps.'_Shared';

            elseif (is_array($Plugins['Plugin:System:Own'])
                && in_array(self::$Plugin, $Plugins['Plugin:System:Own']))
                    $PluginPath = Engine.Apps.self::$Name;

            elseif (is_array($Plugins['Plugin:System:Shared'])
                && in_array(self::$Plugin, $Plugins['Plugin:System:Shared']))
                    $PluginPath = Engine.Apps.'_Shared';
            else
                throw new WTF('Unknown Plugin'.self::$Name.':'.self::$Plugin, 4042);

            if (file_exists($PluginPath.'/'.self::$Plugin.'.'.self::$Interface.'.php'))
                include $PluginPath.'/'.self::$Plugin.'.'.self::$Interface.'.php';
            elseif (file_exists($PluginPath.'/'.self::$Plugin.'.php'))
                include $PluginPath.'/'.self::$Plugin.'.php';
            else
                throw new WTF('Unknown Plugin'.self::$Name.':'.self::$Plugin, 4042);
        }
        catch (Exception $E)
        {
            Timing::Stop('Exec: '.self::$AppID);
            Page::Reset('Errors/Crash');
            Page::Add($E->getCode(), 'code');
        }

        self::$Mode = '';

        Timing::Stop('Exec: '.self::$AppID);
        
        return self::$AppID;
    }

    public static function GetTune($Tune)
    {
        $Application = self::$AppObject->Get('OXTune:'.$Tune);
        
        if (Client::$Authorized)
        {
            $Agent   = Client::$Agent->GetOr(array('OXTune:'.self::$Name.':'.$Tune));

            if (null !== $Agent)
                return $Agent;
            elseif (null !== $Application)
                return $Application;
        }
        else
            return $Application;
    }
}
