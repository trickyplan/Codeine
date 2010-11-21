<?php
 
    class View
    {
        private static $_Processors   = array();
        private static $_Slots        = array();

        private static $_HTTPHeaders  = array();
        private static $_HTTPStatus   = '200 OK';
        
        private static $_Buffers     = array();
        private static $_Body        = array();
        private static $_Rendered    = array();

        public static function Initialize()
        {
            Code::Hook('Core', __CLASS__, 'beforeInitialize');

            self::$_HTTPHeaders['Content-type'] = 'text/html;charset=utf-8';
            self::$_Processors = Core::$Conf['Drivers']['Installed']['Processors'];

            Code::Hook('Core', __CLASS__, 'afterInitialize');
        }

        public static function Body ($Body = null)
        {
            if (null === $Body)
                return self::$_Body[Application::AppID()];
            else
                return self::$_Body[Application::AppID()] = $Body;
        }

        public static function Add ($Data, $Place = 'content', $V = 1)
        {
            if (mb_strpos(self::$_Body[Application::AppID()], $Place) !== false)
            {
                if (mb_strpos($Place, '<') === false)
                        $Place = '<'.$Place.'/>';

                self::$_Body[Application::AppID()] = str_replace($Place , $Data, self::$_Body[Application::AppID()], $V);
            }

            return true;
        }

        public static function AddBuffered ($Data, $Place = 'content')
        {
            if (isset(self::$_Buffers[$Place]))
                self::$_Buffers[$Place] .= $Data;
            else
                self::$_Buffers[$Place] = $Data;

            return true;
        }

        public static function Flush($Place = 'content')
        {
             if (isset(self::$_Buffers[$Place]))
             {
                 self::Add(self::$_Buffers[$Place], $Place);
                 unset(self::$_Buffers[$Place]);
                 return true;
             }
             else
                return false;
        }
        
        public static function Reset($Layout)
        {
            self::$_Body[Application::AppID()] = '<content/>';
            self::Nest($Layout);

            return true;
        }

        public static function Nest ($Layout, $Place = 'content')
        {
            self::Add (View::Load($Layout), $Place);
        }

        public static function Start ($AppID = null)
        {
            if ($AppID == null)
                $AppID = Application::AppID();

            self::$_Body[$AppID] = '<content/>';

            self::Nest ('Main');

            return true;
        }

        private static function Render ($URL)
        {
            $Pockets = array();

            Code::Hook('Core', __CLASS__, 'beforeRender');

            if (!empty(self::$_Processors['Each']))
                foreach(self::$_Processors['Each'] as $Processor)
                    self::$_Body[$URL] = Code::E('Output/Processors','Process', self::$_Body[$URL], $Processor);

            while (preg_match_all('@<exec>([^<]*)<\/exec>@SsUu', self::$_Body[$URL], $Matches))
            {
                foreach ($Matches[1] as $IX => $Match)
                {
                    Application::Route($Match);
                    Application::Run(true);

                    if (empty(self::$_Body[Application::$AppID]))
                    {
                        if (!empty(self::$_Processors['Each']))
                            foreach(self::$_Processors['Each'] as $Processor)
                                self::$_Body[Application::$AppID] = Code::E('Output/Processors','Process', self::$_Body[Application::$AppID], $Processor);
                    }

                    self::$_Body[$URL] = str_replace($Matches[0][$IX], self::$_Body[Application::$AppID], self::$_Body[$URL]);
                }
            }

            if (!empty(self::$_Processors['Post']))
                foreach(self::$_Processors['Post'] as $Processor)
                    self::$_Body[$URL] = Code::E('Output/Processors','Process', self::$_Body[$URL], $Processor);

            Code::Hook('Core', __CLASS__, 'afterRender');

            return self::$_Rendered[$URL] = true;
        }

        public static function Output ($URL, $Echo = true)
        {
            if (!isset(self::$_Rendered[$URL]))
                self::Render($URL);

            Code::Hook('Core', __CLASS__, 'beforeOutput');

            if ($Echo)
            {
                foreach (self::$_HTTPHeaders as $Key => $Value)
                    header($Key.': '.$Value);

                echo self::$_Body[$URL];
                return true;
            }
            else
                return self::$_Body[$URL];
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

            Code::Hook('Core','View','afterLoad', $Layout);

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
            $Data = $Object->Data();
            
            $Structure = self::Load($ID);

            if (is_array($Slots))
                $Structure = str_replace(array_keys($Slots), $Slots, $Structure);

            foreach (self::$_Processors['Fusion'] as $Fuser)
                $Structure = Code::E('Output/Fusers','Fusion', array('Data'=>$Data, 'Structure'=>$Structure, 'Object'=>$Object), $Fuser);

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