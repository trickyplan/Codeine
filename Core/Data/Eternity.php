<?php
/*
 * Data API Implementation E9
 * Codeine5, 2010
 */

    final class Data extends Component
    {
        private   static $_Locked  = false;
        protected static $_Conf    = array();
        protected static $_Stores  = array();
        protected static $_Points  = array();
        public static $Data     = array();

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
                    Code::On('Data.errDataStoreNotFound', $Point);
            }
            else
                Code::On('Data.errDataPointNotFound', $Point);
        }

        public static function Initialize()
        {
            self::$_Conf = self::_Configure(__CLASS__);
        }

        public static function Shutdown ()
        {
            
        }

        public static function AddStore ($ID, $Store)
        {
            return self::$_Conf['Stores'][$ID] = $Store;
        }

        public static function AddPoint ($ID, $Point)
        {
            return self::$_Conf['Points'][$ID] = $Point;
        }

        public static function Mount($Point = 'Default')
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
                               'N' => 'Data.Store.'.self::$_Conf['Stores'][$Store]['Type'],
                               'F' => 'Connect',
                               'Options' => self::$_Conf['Stores'][$Store]
                        ),$Ring)) !== null)
                    Code::On('Data.errDataStoreConnectFailed', $Store);
            }
            else
                Code::On('Data.errDataStoreNotFound', $Store);

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
                        'N'=> 'Data.Routers.'.$Router,
                        'F' => 'Route',
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
                Code::On('Data.errDataRoutingFailed', $Call);

            return $Call;
        }

        public static function Disconnect($Store)
        {
            return Code::Run(array(
                           'N' => 'Data.Store.'.self::$_Conf['Stores'][$Store]['Type'],
                           'F' => 'Disconnect',
                           'Point' => self::$_Stores[$Store]
                      ), Code::Ring1);
        }

        protected static function _CRUD($Method, $Call, $Ring = Code::Ring2)
        {
            // Если некорректный вызов, попробовать роутинг
            if (!self::isValidCall($Call))
                $Call = self::_Route($Call);

            $Call['Point'] = isset($Call['Point'])? $Call['Point']: 'Default';
            $Call['Store'] = self::_getStoreOfPoint($Call['Point']);

            $Call['Options'] = array_merge(self::$_Conf['Stores'][$Call['Store']],self::$_Conf['Points'][$Call['Point']]);
            
            // FIXME Behaviour?
            // Запрещение операций на точке монтирования
            if (isset(self::$_Conf['Options'][$Call['Point']]['No'.$Method]))
            {
                Code::On('Data.Data.'.$Method.'.Denied.Point.'.$Call['Point'], $Call);
                return null;
            }

            // FIXME Behaviour?

            if (isset($Call['Options']['Precondition'][$Method]))
                foreach ($Call['Options']['Precondition'][$Method] as $Precondition)
                    if (!Code::Run($Precondition, $Ring))
                        Code::On('Data.Data.'.$Method.'.Precondition.Failed', $Call);

            Code::On('Data.before'.$Method, $Call);
            Code::On('Data.before'.$Method.'At'.$Call['Store'], $Call);
            Code::On('Data.before'.$Method.'In'.$Call['Point'], $Call);

            // Если точка монтирования ещё не использовалась...
            if (!isset(self::$_Points[$Call['Point']]))
                self::Mount($Call['Point']);

            $Call['Link'] = self::$_Stores[$Call['Store']];
            // Префиксы и постфиксы для ID
            $Call['Prefix'] = isset($Call['Options']['Prefix'])?
                                        $Call['Options']['Prefix']: '';

            $Call['Postfix'] = isset($Call['Options']['Postfix'])?
                                        $Call['Options']['Postfix']: '';

            $Call['N'] = 'Data.Store.'. $Call['Options']['Type'];
            $Call['F'] = $Method;

            $Call['Result'] = Code::Run($Call, $Ring);

            Code::On('Data.after'.$Method, $Call);
            Code::On('Data.after'.$Method.'At'.$Call['Store'], $Call);
            Code::On('Data.after'.$Method.'In'.$Call['Point'], $Call);

            if (isset($Call['Options']['Postcondition'][$Method]))
                foreach ($Call['Options']['Postcondition'][$Method] as $Postcondition)
                    if (!Code::Run($Postcondition, $Ring))
                    {
                        Code::On('Data.errDataPostconditionFailed', $Call);
                        Code::On('Data.errDataPostcondition'.$Method.'Failed', $Call);
                        Code::On('Data.errDataPostcondition'.$Call['Point'].'Failed', $Call);
                    }

            if (isset($Call['Options']['Postcondition'][$Method]))
                foreach ($Call['Options']['Postcondition'][$Method] as $Postcondition)
                    if (!Code::Run($Postcondition, $Ring))
                    {
                        Code::On('Data.errDataPostconditionFailed', $Call);
                        Code::On('Data.errDataPostcondition'.$Call['Store'].'Failed', $Call);
                        Code::On('Data.errDataPostcondition'.$Call['Point'].'Failed', $Call);
                    }

            return $Call;
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
            if (!isset($Call['Point'])) $Call['Point'] = 'Default';
            
            if (isset(self::$_Conf['Points'][$Call['Point']]['Map']))
                $Call = Code::Run(
                    array(
                        'N' => 'Data.Map.'.self::$_Conf['Points'][$Call['Point']]['Map'],
                        'F' => 'Create',
                        'Call'=> $Call
                    ));

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
            $Call = self::_CRUD('Read', $Call, $Ring);

            if (isset(self::$_Conf['Points'][$Call['Point']]['Format']) && $Call['Result'] !== null)
            {
                if (($Call['Result'] = Code::Run(array(
                              'N' => 'Data.Formats.'.
                                     self::$_Conf['Points'][$Call['Point']]['Format'],
                              'F' => 'Decode',
                              'Input' => $Call['Result']), $Ring)) === null)
                    Code::On('Data.errDataReadFormatDecodeFailed', $Call);
            }

            if (isset($Call['Options']['Map']))
                $Call['Result'] = Code::Run(
                    array(
                        'N' => 'Data.Map.'.$Call['Options']['Map'],
                        'F' => 'afterRead',
                        'Result'=> $Call['Result']
                    ));

            return $Call['Result'];
        }

        public static function Update ($Call, $Mode = Code::Ring2)
        {
            if (isset(self::$_Conf['Points'][$Call['Point']]['Map']))
                $Call = Code::Run(
                    array(
                        'N' => 'Data.Map.'.self::$_Conf['Points'][$Call['Point']]['Map'],
                        'F' => 'Update',
                        'Call'=> $Call
                    ));
            
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
               Code::On('Data.Data.Locate.NotFound', $Path.':'.$Name);
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
