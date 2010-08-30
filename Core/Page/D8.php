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
        
        self::$Processors['Each']   = Core::$Conf['Options']['Processors']['Each'];
        self::$Processors['Post']   = Core::$Conf['Options']['Processors']['Post'];
        self::$Processors['Pre']    = Core::$Conf['Options']['Processors']['Pre'];
        self::$Processors['Fusion'] = Core::$Conf['Options']['Processors']['Fusion'];
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

    public static function Growl($Data)
    {
        return self::JS('$.jGrowl("'.$Data.'");');
    }

    public static function Add ($Data, $Place = 'content', $V = 1)
    {
        if (mb_strpos(self::$Body[Application::$AppID], $Place) !== false)
            self::$Body[Application::$AppID] = str_replace('<'.$Place.'/>' , $Data, self::$Body[Application::$AppID], $V);
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
        Timing::Go('Nesting');
            self::Add (Page::Load($Layout), $Place);
        Timing::Stop('Nesting');
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
        
        return self::$Rendered[$URL] = true;
    }

    public static function Output ($URL, $Echo = true)
    {
        if (!isset(self::$Rendered[$URL]))
        {
            self::Render($URL);

            if (preg_match_all('@<counter name="(.*)"\/>@SsUu', self::$Body[$URL], $Matches))
                foreach ($Matches[0] as $IX => $Match)
                    if (isset(Log::$Counters[$Matches[1][$IX]]))
                        self::$Body[$URL] = str_replace($Match, Log::$Counters[$Matches[1][$IX]], self::$Body[$URL]);
                    else
                        self::$Body[$URL] = str_replace($Match, ' No', self::$Body[$URL]);

            // self::$Body[$URL] = str_replace('<RSi/>', round((mb_strlen(self::$Body[$URL])/1024)/(Timing::Lap('Fusion')),2),self::$Body[$URL]);
            self::$Body[$URL] = str_replace('<Memory/>',number_format((memory_get_usage()/1048576), 3, '.', ' '), self::$Body[$URL]);
            self::$Body[$URL] = str_replace('<Timer/>',(round(microtime(true)-Core::$StartTime, 3)*1000), self::$Body[$URL]);
        }
        
        // file_put_contents(Root.Data.'_Temp/'.sha1(Client::$UID.$URL).'.html', self::$Body[$URL]);

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
    }

    public static function JS ($Code)
    {
        self::$JS[md5($Code)] = $Code;
    }

    public static function JSFile ($File)
    {
        if (mb_substr($File,0,1) == '~')
            $File = EngineShared.'JS/'.mb_substr($File,1);
        else
            $File = Root.$File;
        
        self::$JSIncludes[$File] = $File;
    }

    public static function CSSFile ($File)
    {
        if (mb_substr($File,0,1) == '~')
            $File = EngineShared.mb_substr($File,1);
        else
            $File = Root.$File;

        self::$CSSIncludes[$File] = $File;
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
            Timing::Go('Fusion');

            $Data = $Object->Data();
            $Structure = self::Load($ID);

            if (is_array($Slots))
                $Structure = str_replace(array_keys($Slots), $Slots, $Structure);

            foreach (self::$Processors['Fusion'] as $Fuser)
                $Structure = Code::E('Output/Fusers','Fusion', array('Data'=>$Data, 'Structure'=>$Structure, 'Object'=>$Object), $Fuser);

            Timing::Stop('Fusion');

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