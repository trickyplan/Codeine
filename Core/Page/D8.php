<?php
 
class Page
{    
    public static $Processors   = array();
    public static $Slots        = array();
    public static $JS           = array();
    public static $JSIncludes   = array();

    public static $HTTPHeaders  = array();
    public static $HTTPStatus   = '200 OK';
    public static $CSS          = array();
    public static $CSSIncludes  = array();
    public static $LRCache      = array();
    public static $AppCache     = array();
    public static $Commands     = array();

    private static $Buffers     = array();
    private static $Body        = array();
    private static $Rendered    = array();

    public static function Initialize()
    {
        ob_start('Server::FatalHandler');
        self::$HTTPHeaders['Content-type'] = 'text/html;charset=utf-8';
        self::$Processors = Core::$Conf['Drivers']['Installed']['Processors'];
    }

    public static function Body ($Body = null)
    {
        if (null === $Body)
            return self::$Body[Application::$AppID];
        else
            return self::$Body[Application::$AppID] = $Body;
    }

    
    public static function Search ($Data)
    {
        if (mb_strpos(self::$Body[Application::$AppID],$Data)!== false)
            return true;
        else
            return false;
    }

    public static function Add ($Data, $Place = 'content', $V = 1)
    {
        if (mb_strpos(self::$Body[Application::$AppID], $Place) !== false)
        {
            if (mb_strpos($Place, '<') === false)
                    $Place = '<'.$Place.'/>';

            self::$Body[Application::$AppID] = str_replace($Place , $Data, self::$Body[Application::$AppID], $V);
        }
    }

    public static function AddBuffered ($Data, $Place = 'content')
    {
        if (isset(self::$Buffers[$Place]))
            self::$Buffers[$Place] .= $Data;
        else
            self::$Buffers[$Place] = $Data;
        
        return true;
    }

    public static function Flush($Place = 'content')
    {
         if (isset(self::$Buffers[$Place]))
         {
             self::Add(self::$Buffers[$Place], $Place);
             unset(self::$Buffers[$Place]);
             return true;
         }
         else
            return false;
    }
    public static function Reset($Layout)
    {
        self::$Body[Application::$AppID] = '<content/>';
        self::Nest($Layout);
    }

    public static function Nest ($Layout, $Place = 'content')
    {
        Profiler::Go('Nesting');
            self::Add (Page::Load($Layout), $Place);
        Profiler::Stop('Nesting');
    }

    public static function Start ($AppID = null)
    {
        if ($AppID == null)
            $AppID = Application::$AppID;

        self::$Body[$AppID] = '<content/>';
        
        self::Nest ('Main');
    }

    private static function Render ($URL)
    {
        $Pockets = array();
        
        if (!empty(self::$Processors['Each']))
            foreach(self::$Processors['Each'] as $Processor)
                self::$Body[$URL] = Code::E('Output/Processors','Process', self::$Body[$URL], $Processor);

        while (preg_match_all('@<exec>([^<]*)<\/exec>@SsUu', self::$Body[$URL], $Matches))
        {
            foreach ($Matches[1] as $IX => $Match)
            {
                Application::Route($Match);
                Application::Run(true);

                if (empty(self::$Body[Application::$AppID]))
                {
                    if (!empty(self::$Processors['Each']))
                        foreach(self::$Processors['Each'] as $Processor)
                            self::$Body[Application::$AppID] = Code::E('Output/Processors','Process', self::$Body[Application::$AppID], $Processor);
                }

                self::$Body[$URL] = str_replace($Matches[0][$IX], self::$Body[Application::$AppID], self::$Body[$URL]);
            }
        }

        if (!empty(self::$Processors['Post']))
            foreach(self::$Processors['Post'] as $Processor)
                self::$Body[$URL] = Code::E('Output/Processors','Process', self::$Body[$URL], $Processor);

        Application::$AppID = $URL;

        return self::$Rendered[$URL] = true;
    }

    public static function Output ($URL, $Echo = true)
    {
        if (!isset(self::$Rendered[$URL]))
        {
            self::Render($URL);

            Code::Hook('Core', __CLASS__, 'Output');
        }
        
        if ($Echo)
        {            
            foreach (self::$HTTPHeaders as $Key => $Value)
                header($Key.': '.$Value);
            
            echo self::$Body[$URL];
            return true;
        }
        else
            return self::$Body[$URL];
    }

    public static function CSS ($Code)
    {
        self::$CSS[md5($Code)] = $Code;
        return true;
    }

    public static function JS ($Code)
    {
        self::$JS[md5($Code)] = $Code;
        return true;
    }

    public static function JSFile ($File)
    {
        if (mb_substr($File,0,1) == '~')
            $File = EngineShared.'JS/'.mb_substr($File,1);
        else
            $File = Root.$File;
        
        self::$JSIncludes[$File] = $File;
        return true;
    }

    public static function CSSFile ($File)
    {
        if (mb_substr($File,0,1) == '~')
            $File = EngineShared.mb_substr($File,1);
        else
            $File = Root.$File;

        self::$CSSIncludes[$File] = $File;
        return true;
    }

    public static function Load ($ID)
    {
        $Layout = Data::Read('_Layout','{"I":'.json_encode($ID).',"Interface":"'.Application::$Interface.'"}');

        if ($Layout === null)
            $Layout = '<content/>';
        
        // TODO WHILE
        if (preg_match_all('@<nest>(.*)<\/nest>@SsUu', $Layout,  $Matches))
            foreach ($Matches[1] as $IX => $Match)
                $Layout = str_replace($Matches[0][$IX], '<!-- '.$Match.' -->'.self::Load($Match), $Layout);

        foreach (self::$Processors['Pre'] as $Fuser)
                $Layout = Code::E('Output/Fusers','Fusion', array('Structure'=>$Layout), $Fuser);
            
        return $Layout;
    }

    public static function Fusion ($ID, $Object, $Slots = false)
    {
        self::Load($ID);

        $Result = '';
        $Type = get_class($Object);

        if (!in_array($Type, array('Object', 'Collection')))
            return null;

        if (!$Object->Loaded)
            $Object->Load();

        switch ($Type)
        {
            case 'Object':
                $Result = self::_Fusion($ID, $Object, $Slots);
            break;

            case 'Collection':
                $IC = 1;
                foreach($Object->_Items as $cObject)
                    $Result.= str_replace('<#/>', $IC++,self::_Fusion($ID, $cObject, $Slots));

            break;
        }

        return $Result;
    }

    private static function _Fusion ($ID, $Object, $Slots = false)
	{
            Profiler::Go('Fusion');

            $Data = $Object->Data();
            $Structure = self::Load($ID);

            if (is_array($Slots))
                $Structure = str_replace(array_keys($Slots), $Slots, $Structure);

            foreach (self::$Processors['Fusion'] as $Fuser)
                $Structure = Code::E('Output/Fusers','Fusion', array('Data'=>$Data, 'Structure'=>$Structure, 'Object'=>$Object), $Fuser);

            Profiler::Stop('Fusion');

            return $Structure;
       }

       public static function Replace ($ID, $TRs = null)
       {
           $Layout = self::Load($ID);

           if (null !== $TRs)
                foreach ($TRs as $Key => $Value)
                    $Layout = str_replace($Key, $Value, $Layout);

           return $Layout;
       }

       public static function Get($ID)
       {
           return self::Load($ID);
       }
}