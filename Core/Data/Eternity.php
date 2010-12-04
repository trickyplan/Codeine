<?php
/*
 * Data API Implementation E9
 * Codeine5, 2010
 */

    final class Data extends Component
    {
        private   static $_Locked  = false;
        protected static $_Conf    = array();
        protected static $Data     = array();
        protected static $_Stores  = array();
        protected static $_Points  = array();

        protected static function _LockData ()
        {
            return self::$_Locked = !self::$_Locked;
        }

        private static function _getStoreOfPoint($Point)
        {
            if (isset(self::$_Conf['Points'][$Point]))
            {
                if (isset(self::$_Conf['Points'][$Point]['Store']))
                    return self::$_Conf['Points'][$Point]['Store'];
                else
                    Code::On(__CLASS__, 'errDataStoreNotFound', $Point);
            }
            else
                Code::On(__CLASS__, 'errDataPointNotFound', $Point);
        }

        public static function Initialize()
        {
            self::$_Conf = self::_Configure(__CLASS__);
        }

        public static function Shutdown ()
        {
            
        }

        public static function Mount($Point)
        {
            if (isset(self::$_Conf['Points'][$Point]['Ring']))
                $Mode = self::$_Conf['Points'][$Point]['Ring'];
            else
                $Mode = 2;
            
            self::Connect(self::_getStoreOfPoint($Point), $Mode);
            return self::$_Points[$Point] = $Point;
        }

        public static function Unmount()
        {

        }
        
        public static function Connect ($Store, $Ring)
        {
            if (!isset(self::$_Stores[$Store]))
            {
                if ((self::$_Stores[$Store] =
                    Code::Run(array(
                               'F' => 'Data/Store/'.self::$_Conf['Stores'][$Store]['Type'].'::Connect',
                               'Point' => self::$_Conf['Stores'][$Store]
                        ),$Ring)) !== null)
                    Code::On(__CLASS__, 'errDataStoreConnectFailed', $Store);
            }
            else
                Code::On(__CLASS__, 'errDataStoreNotFound', $Store);

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
                        'F'=> 'Data/Routers/'.$Router.'::Route',
                        'Input' => $Call
                    ), Code::Ring1
                );

                // Если что-то получилось, то выходим из перебора
                if (self::isValidCall($NewCall))
                    break;
            }

            // Если хоть один роутер вернул результат...
            if ($NewCall !== null)
                $Call = $NewCall;
            else
                Code::On(__CLASS__, 'errDataRoutingFailed', $Call);

            return $Call;
        }

        public static function Disconnect($Store)
        {
            return Code::Run(array(
                           'F' => 'Data/Store/'.self::$_Conf['Stores'][$Store]['Type'].'::Disconnect',
                           'Point' => self::$_Stores[$Store]
                      ), Code::Ring1);
        }

        protected static function _CRUD($Method, $Call, $Ring = Code::Ring2)
        {
            // Если некорректный вызов, попробовать роутинг
            if (!self::isValidCall($Call))
                $Call = self::_Route($Call);

            $Point = $Call['Point'];
            $Store = self::_getStoreOfPoint($Point);

            // Запрещение операции на хранилище
            if (isset(self::$_Conf['Stores'][$Store]['No'.$Method]))
            {
                Code::On(__CLASS__, 'errData'.$Store.'No'.$Method, $Call);
                return null;
            }
            
            // Запрещение операций на точке монтирования
            if (isset(self::$_Conf['Point'][$Point]['No'.$Method]))
            {
                Code::On(__CLASS__, 'errData'.$Point.'No'.$Method, $Call);
                return null;
            }

            if (isset(self::$_Conf['Stores'][$Store]['Precondition'][$Method]))
                foreach (self::$_Conf['Stores'][$Store]['Precondition'][$Method] as $Precondition)
                    if (!Code::Run($Precondition, $Ring))
                    {
                        Code::On(__CLASS__, 'errDataPreconditionFailed', $Call);
                        Code::On(__CLASS__, 'errDataPrecondition'.$Method.'Failed', $Call);
                        Code::On(__CLASS__, 'errDataPrecondition'.$Call['Point'].'Failed', $Call);
                    }

            if (isset(self::$_Conf['Points'][$Point]['Precondition'][$Method]))
                foreach (self::$_Conf['Points'][$Point]['Precondition'][$Method] as $Precondition)
                    if (!Code::Run($Precondition, $Ring))
                    {
                        Code::On(__CLASS__, 'errDataPreconditionFailed', $Call);
                        Code::On(__CLASS__, 'errDataPrecondition'.$Method.'Failed', $Call);
                        Code::On(__CLASS__, 'errDataPrecondition'.$Point.'Failed', $Call);
                    }

            Code::On(__CLASS__, 'before'.$Method, $Call);
            Code::On(__CLASS__, 'before'.$Method.'At'.$Store, $Call);
            Code::On(__CLASS__, 'before'.$Method.'In'.$Point, $Call);

            // Если точка монтирования ещё не использовалась...
            if (!isset(self::$_Points[$Point]))
                self::Mount($Point);
            
            // Префиксы и постфиксы для ID
            $Prefix = isset(self::$_Conf['Points'][$Point]['Prefix']) ?
                            self::$_Conf['Points'][$Point]['Prefix']: '';

            $Postfix = isset(self::$_Conf['Points'][$Point]['Postfix']) ?
                            self::$_Conf['Points'][$Point]['Postfix']: '';


            $Call['Result'] = Code::Run(array(
                           'F' => 'Data/Store/'.self::$_Conf['Stores'][$Store]['Type'].'::'.$Method,
                           'Point' => self::$_Conf['Points'][$Point],
                           'Store' => self::$_Stores[$Store],
                           'Data' => $Call,
                           'Postfix' => $Postfix,
                           'Prefix' => $Prefix
                      ), $Ring);

            Code::On(__CLASS__, 'after'.$Method, $Call);
            Code::On(__CLASS__, 'after'.$Method.'At'.$Store, $Call);
            Code::On(__CLASS__, 'after'.$Method.'In'.$Point, $Call);

            if (isset(self::$_Conf['Stores'][$Store]['Postcondition'][$Method]))
                foreach (self::$_Conf['Stores'][$Store]['Postcondition'][$Method] as $Postcondition)
                    if (!Code::Run($Postcondition, $Ring))
                    {
                        Code::On(__CLASS__, 'errDataPostconditionFailed', $Call);
                        Code::On(__CLASS__, 'errDataPostcondition'.$Method.'Failed', $Call);
                        Code::On(__CLASS__, 'errDataPostcondition'.$Point.'Failed', $Call);
                    }

            if (isset(self::$_Conf['Points'][$Point]['Postcondition'][$Method]))
                foreach (self::$_Conf['Points'][$Point]['Postcondition'][$Method] as $Postcondition)
                    if (!Code::Run($Postcondition, $Ring))
                    {
                        Code::On(__CLASS__, 'errDataPostconditionFailed', $Call);
                        Code::On(__CLASS__, 'errDataPostcondition'.$Method.'Failed', $Call);
                        Code::On(__CLASS__, 'errDataPostcondition'.$Point.'Failed', $Call);
                    }

            return $Call['Result'];
        }

        public static function __callStatic($Operation, $Arguments)
        {
            return self::_CRUD($Operation, $Arguments[0], isset($Arguments[1])? $Arguments[1]:Code::Ring2);
        }

        /**
         * @description Создаёт данные в Хранилище
         * @static
         * @param  $Call - данные
         * @param int $Ring - кольцо, необходимо для низкоуровневых вызовов
         * @return mixed
         */
        public static function Create ($Call, $Mode = Code::Ring2)
        {
            return self::_CRUD('Create', $Call, $Mode);
        }

        /**
         * @description Читает данные из Хранилища
         * @static
         * @param  $Call - выборка
         * @param int $Ring - кольцо, необходимо для низкоуровневых вызовов
         * @return mixed
         */
        public static function Read($Call, $Ring = Code::Ring2)
        {
            $Result = self::_CRUD('Read', $Call, $Ring);

            if (isset(self::$_Conf['Points'][$Call['Point']]['Format']) && $Result !== null)
                $Result = Code::Run(array(
                              'F' => 'Data/Formats/'.
                                     self::$_Conf['Points'][$Call['Point']]['Format'].'::Decode',
                              'Input' => $Result
                                 ), $Ring);
            if ($Result === null)
                Code::On(__CLASS__, 'errDataReadFormatDecodeFailed', $Call);

            return $Result;
        }

        public static function Update ($Call, $Mode = Code::Ring2)
        {
            return self::_CRUD('Update', $Call, $Mode);
        }

        public static function Delete ($Call, $Mode = Code::Ring2)
        {
            return self::_CRUD('Delete', $Call, $Mode);
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
               Code::On(__CLASS__, 'errLocateNotFound', $Path.':'.$Name);
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
