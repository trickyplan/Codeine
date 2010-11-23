<?php
/*
 * Data API Implementation E9
 * Codeine5, 2010
 */

    class Data extends Component
    {
        protected static $_Conf;
        public static $Data        = array();
        protected static $_Stores  = array();
        protected static $_Points  = array();

        private static function _Point2Storage($Point)
        {
            if (isset(self::$_Conf['Points'][$Point]['Store']))
                return self::$_Conf['Points'][$Point]['Store'];
            else
                var_dump($Point);
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
            return self::$_Points[$Point] = $Point;
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

        protected static function _Route ($Call)
        {
            // Для каждого определенного роутера...
            foreach (self::$_Conf['Routers'] as $Router)
            {
                // Пробуем роутер из списка...
                $NewCall = Code::Run(
                    array(
                        'F'=> 'Data/Routers/'.$Router.'/Route',
                        'Input' => $Call
                    ), Code::Internal
                );

                // Если что-то получилось, то выходим из перебора
                if (self::isValidCall($NewCall))
                    break;
            }

            // Если хоть один роутер вернул результат...
            if ($NewCall !== null)
                $Call = $NewCall;
            else
                throw new WTF('404 Data Router');

            return $Call;
        }

        public static function Disconnect($Store)
        {
            return Code::Run(array(
                           'F' => 'Data/Store/'.self::$_Conf['Stores'][$Store]['Type'].'/Disconnect',
                           'Point' => self::$_Stores[$Store]
                      ));
        }

        protected static function _CRUD($Method, $Call)
        {
            if (!self::isValidCall($Call))
                $Call = self::_Route($Call);

            if (!isset(self::$_Points[$Call['Point']]))
                self::Mount($Call['Point']);

            $Store = self::_Point2Storage($Call['Point']);

            return Code::Run(array(
                           'F' => 'Data/Store/'.self::$_Conf['Stores'][$Store]['Type'].'/'.$Method,
                           'Point' => self::$_Conf['Points'][$Call['Point']],
                           'Store' => self::$_Stores[$Store],
                           'Data' => $Call
                      ));
        }

        public static function Create($Call)
        {
            return self::_CRUD('Create', $Call);
        }

        public static function Read($Call)
        {
            return self::_CRUD('Read', $Call);
        }

        public static function Update($Call)
        {
            return self::_CRUD('Update', $Call);
        }

        public static function Delete($Call)
        {
            return self::_CRUD('Delete', $Call);
        }

        public static function Exist($Call)
        {
            return self::_CRUD('Exist', $Call);
        }

        public static function Version($Call)
        {
            return self::_CRUD('Version', $Call);
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

        public static function isValidCall($Call)
        {
            return (is_array($Call) && isset($Call['Point']));
        }
    }
