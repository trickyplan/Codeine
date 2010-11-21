<?php
/*
 * Data API Implementation E9
 * Codeine5, 2010
 */

    class Data extends Component
    {
        protected static $_Conf;
        public static $Data      = array();
        private static $_Stores  = array();
        private static $_Points  = array();

        private static function _Point2Storage($Point)
        {
            return self::$_Conf['Points'][$Point]['Store'];
        }

        public static function Initialize()
        {
            self::$_Conf = self::_Configure(__CLASS__);
        }

        public static function Shutdown ()
        {
            
        }
                


        public static function Structure($Scope)
        {
            
        }

        public static function Mount($Point)
        {
            self::Connect(self::_Point2Storage($Point));
        }

        public static function Unmount()
        {

        }
        
        public static function Connect ($Store)
        {
            if (!isset(self::$_Stores[$Store]))
                self::$_Stores[$Store] =
                    Code::Run(array(
                               'F' => 'Data/Store/'.self::$_Conf['Stores'][$Store]['Type'].'/Connect',
                               'Point' => self::$_Conf['Stores'][$Store]
                        ));

            return self::$_Stores[$Store];
        }

        public static function Disconnect($Store)
        {
            return Code::Run(array(
                           'F' => 'Data/Store/'.self::$_Conf['Stores'][$Store]['Type'].'/Disconnect',
                           'Point' => self::$_Stores[$Store]
                      ));
        }

        public static function Create($Point, $New)
        {
            $Store = self::_Point2Storage($Point);
            return Code::Run(array(
                           'F' => 'Data/Store/'.self::$_Conf['Stores'][$Store]['Type'].'/Create',
                           'Point' => self::$_Conf[$Point],
                           'Data' => $New
                      ));
        }

        public static function Read($Point, $Where)
        {
            $Store = self::_Point2Storage($Point);
            return Code::Run(array(
                           'F' => 'Data/Store/'.self::$_Conf['Stores'][$Store]['Type'].'/Read',
                           'Point' => self::$_Conf['Points'][$Point],
                           'Store' => self::$_Stores[$Store],
                           'Data' => $Where
                      ));
        }

        public static function Update($Point, $Old, $New)
        {
            $Store = self::_Point2Storage($Point);
            return Code::Run(array(
                           'F' => 'Data/Store/'.self::$_Conf['Stores'][$Store]['Type'].'/Update',
                           'Point' => self::$_Stores[$Store],
                           'New' => $New,
                           'Old' => $Old
                      ));
        }

        public static function Delete($Point, $Where)
        {
            $Store = self::_Point2Storage($Point);
            return Code::Run(array(
                           'F' => 'Data/Store/'.self::$_Conf['Stores'][$Store]['Type'].'/Delete',
                           'Point' => self::$_Stores[$Store],
                           'Data' => $Where
                      ));
        }

        public static function Exist($Point, $Where)
        {
            $Store = self::_Point2Storage($Point);
            return Code::Run(array(
                           'F' => 'Data/Store/'.self::$_Conf['Stores'][$Store]['Type'].'/Exist',
                           'Point' => self::$_Stores[$Store],
                           'Data' => $Where
                      ));
        }


        public static function Locate ($Path, $Name)
        {
            if (!is_array($Name))
                $Name = array($Name);

            foreach ($Name as $cName)
            {
                $cName = self::Path($Path).$cName;

                if (file_exists(Root.$cName))
                    $R = Root.$cName;
                elseif (file_exists(Engine.$cName))
                    $R = Engine.$cName;
                else
                    foreach (self::$_Conf['Shared'] as $Shared)
                        if (file_exists($Shared.DS.$cName))
                        {
                            $R = $Shared.DS.$cName;
                            break;
                        }
           }

           if (isset($R))
               return $R;
           else
               return Log::Error ('Not located '.$Name);
        }

        public static function Path ($Key)
        {
            if (isset(self::$_Conf['Paths'][$Key]))
                return self::$_Conf['Paths'][$Key].'/';
            else
                return $Key.'/';
        }
    }
