<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Core Module
     * @description: NextGen Driver Module
     * @package Core
     * @subpackage Driver
     * @version 0.8
     * @date 28.10.10
     * @time 2:16
     */
    
    final class Code extends Component
    {
        const Ring0    = 0;
        const Ring1    = 1;
        const Ring2    = 2;

        private static $_Locked = false;
        
        protected static $_Stack     = array();
        protected static $_Conf      = array();
        protected static $_Tree      = array();
        protected static $_Hooks     = array();
        protected static $_Contracts = array();
        protected static $_Functions = array();
        protected static $_Aliases   = array();

        protected static $_LastCall = null;
        protected static $_Registration = array();

        protected static function _LockCode()
        {
            return self::$_Locked = !self::$_Locked;
        }

        public static function Initialize ()
        {
            self::$_Stack = new SplStack();

            self::$_Conf  = self::_Configure(__CLASS__);
            self::On('Code.onInitialize');
        }

        public static function Conf($Key)
        {
            if (isset(self::$_Conf[$Key]))
                return self::$_Conf[$Key];
            else
            {
                self::On('Code.Conf.Key.NotFound', $Key);
                return null;
            }
        }

        /**
         * @description Создаёт событие, и запускает его обработчики
         * @static
         * @param  $Class - класс события (Code|Message|Data...)
         * @param  $Event - имя события
         * @param array $Data - дополнительные аргументы
         * @return mixed|null
         */
        public static function On ($Event, $Data = array())
        {
            $Data['Event'] = $Event;
            
            if (isset(self::$_Conf['Hooks'][$Event]))
            {
                if (self::$_Conf['Hooks'][$Event] !== null)
                {
                    return Code::Run(
                        array(
                            'Calls' => self::$_Conf['Hooks'][$Event],
                            'Data'  => $Data
                        ), Code::Ring1, 'Feed');
                }
                else
                    return null;
            }
            else
                self::On('Code.Event.NotSpecified', array('NewEvent' => $Event));
            
            //echo $Event.'<br/>';
        }

        /**
         * @description Добавить обработчик события во время исполнения
         * @static
         * @param  $Class - класс события
         * @param  $Event - имя события
         * @param  $ID    - имя обработчика
         * @param  $Call  - валидный вызов
         * @return bool
         */
        public static function AddHook ($Class, $Event, $ID, $Call)
        {
            return self::$_Hooks[$Class][$Event][$ID] = $Call;
        }

        /**
         * @description Удалить обработчик события во время исполнения
         * @static
         * @param  $Class - класс события
         * @param  $Event - имя события
         * @param  $ID    - имя обработчика
         * @return bool
         */
        public static function DelHook ($Class, $Event, $ID)
        {
            unset (self::$_Hooks[$Class][$Event][$ID]);
            return true;
        }

        public static function Shutdown ()
        {
            self::On('Code.onShutdown');
        }

        /**
         * @description Проверяет, является ли аргумент корректным вызовом.
         * @static
         * @param  $Call
         * @return bool
         */
        public static function isValidCall($Call)
        {
            return is_array($Call) && isset($Call['N']);
        }

        /**
         * @description Загружает контракт.
         * @static
         * @param  $Namespace
         * @param  $Function
         * @param  $Driver
         * @return null
         */
        
        public static function LoadContract($Call, $Mode = Code::Ring2)
        {
            if (!isset(self::$_Contracts[$Call['N']][$Call['F']]))
            {
                // FIXME OPTME
                $Default = array($Call['F'] => array());

                if ($Mode == Code::Ring2)
                {
                    $DriverContract   = null;
                    $GroupContract = null;

                    if (isset($Call['D']))
                    {
                        $DriverContract =
                            Data::Read(
                                array('Point' => 'Contract',
                                      'Where' => array(
                                            'ID' => $Call['N'].'/'.$Call['D']
                                      )
                            ), Code::Ring1);
                    }

                    $GroupContract =
                        Data::Read(
                            array('Point' => 'Contract',
                                  'Where' => array(
                                        'ID' => $Call['N'].'/'.$Call['G']
                                  )
                        ), Code::Ring1);

                    if ($GroupContract !== null || $DriverContract !== null)
                    {
                        $Contract = self::ConfWalk($GroupContract, $DriverContract);

                        if (isset($Contract[$Call['F']]))
                            $Contract = $Contract[$Call['F']];
                        else
                        {
                            self::On('Code.LoadContract.Function.NotFound', $Call);
                            $Contract = array();
                        }
                    }
                    else
                        $Contract = array();
                }
                else
                    $Contract = $Default;

                self::$_Contracts[$Call['N']][$Call['F']] = $Contract;
            }
            else
                $Contract = self::$_Contracts[$Call['N']][$Call['F']];

            if (isset($Call['Override']))
                $Contract =  self::ConfWalk($Contract, $Call['Override']);

            $Call['Contract'] = $Contract;
            
            return $Call;
        }

        /**
         * Метод роутинга
         * @static
         * @param  $Call
         * @return mixed|null
         */
        protected static function _Route ($Call)
        {
            $NewCall = null;

            // Для каждого определенного роутера...
            foreach (self::$_Conf['Routers'] as $Router)
            {
                // Пробуем роутер из списка...
                $NewCall = Code::Run(
                    array(
                        'N' => 'Code.Routers',
                        'F'  => 'Route',
                        'D' => $Router,
                        'Call' => $Call
                    ), Code::Ring1
                );

                // Если что-то получилось, то выходим из перебора
                if (self::isValidCall($NewCall))
                    break;
            }

            // Если хоть один роутер вернул результат...
            if ($NewCall === null)
                self::On('Code.Routing.Failed', $Call);
            else
                $Call = $NewCall;

            return $Call;
        }

        protected static function SetNamespace($Namespace, $Driver)
        {
            return self::$_Registration = array('N' => $Namespace, 'D'=> $Driver);

        }

        public static function Fn($Function, $Code = null)
        {
            if (null !== $Code)
            {
                if (false !== $Code)
                    self::$_Functions
                        [self::$_Registration['N']]
                            [self::$_Registration['D']][$Function] = $Code;
                else
                    unset(self::$_Functions
                        [self::$_Registration['N']]
                            [self::$_Registration['D']][$Function]);
            }
            else
                if (isset(self::$_Functions
                        [self::$_Registration['N']]
                            [self::$_Registration['D']][$Function]))
                return self::$_Functions
                            [self::$_Registration['N']]
                                [self::$_Registration['D']][$Function];
        }

        protected static function _LoadSource($Call)
        {
            if (!isset($Contract['Engine']))
                $Call['Contract']['Engine'] = 'Include';

            switch ($Call['Contract']['Engine'])
            {
                case 'Include':
                    if (isset($Call['Contract']['Source']))
                        $Filename = Data::Locate('Code', $Call['Contract']['Source']);
                    else
                        $Filename = Data::Locate('Code', $Call['N'].'/'.$Call['D'].'.php');
                        
                    if ($Filename)
                        return (include $Filename);
                    else
                        self::On('Code.LoadSource.Include.FileNotFound', $Call);
                break;

                case 'Code':
                    if(isset($Call['Contract']['Source']))
                        eval('self::Fn(\''.$Call['F'].'\',
                            function ($Call) {'.$Call['Contract']['Source'].'});');
                    else
                        self::On('Code.Source.InContractNotSpecified', $Call);
                break;

                case 'Data':
                    if(isset($Call['Contract']['Source']))
                        eval('self::Fn(\''.$Call['F'].'\',
                            function ($Call) {'.Data::Read('Source', $Call['Contract']['Source']).'});');
                    else
                        self::On('Code.Source.DataInContractNotSpecified', $Call);
                break;

                default:
                    self::On('Code.Contract.UnknownEngine', $Call['Contract']['Engine']);
                break;
            }
        }

        protected static function _Do($Call)
        {
            $F = self::Fn($Call['F']);

            if (null == $F)
            {
                self::On('Code.Function.IsNotCallable', $Call);
                return null;
            }
            elseif (is_callable($F) or ($F instanceof Closure))
                return $F($Call);
        }

        /**
         * @description Главный метод Codeine, запускает код.
         * @param  $Call - запрос, ассоциативный массив или совместимый аргумент
         * @return mixed $Result - результат
         */
        
        public static function Run ($Call, $Mode = Code::Ring2, $Runner = null, $Executor = null)
        {
            self::$_Stack->push($Call);
            
            if (isset(self::$_Conf['Limit']['NestedCalls'])
                    && self::$_Stack->count() > self::$_Conf['Limit']['NestedCalls'])
                        self::On('Code.CodeOverflowFault', $Call);

            // FIXME Behaviour!
            if ($Runner !== null)
            {
                self::$_Stack->pop();
                return self::Run(
                    array(
                         'N' => 'Code.Runners.'.$Runner,
                         'F' => 'Run',
                         'Call' => $Call,
                         'Mode' => $Mode
                          ), $Mode);
            }
            
            // FIXME Behaviour!
            if ($Executor !== null)
            {
                self::$_Stack->pop();
                return self::Run(
                    array(
                         'N' => 'Code.Executors.'.$Executor,
                         'F' => 'Run',
                         'Call' => $Call,
                         'Mode' => $Mode
                          ), $Mode);
            }
            
            $Return = null;

            // Проверка на псевдонимы. Псевдонимы проверяются при определении.
            // Если передан не готовый объект запроса, а что-то иное, вызываем роутинг.
            
            // FIXME Behaviour!
            if (is_string($Call) && isset(self::$_Aliases[$Call]))
                $Call = self::$_Aliases[$Call];

            if (!self::isValidCall($Call))
                $Call = self::_Route($Call);

            if (!isset($Call['N']))
            {
                self::On('Code.Code.Run.Namespace.NotDefined', $Call);
                return null;
            }
            else
                $N = preg_split('@\.@', $Call['N']);

            $Call['N'] = implode('/', $N);

            if (!isset($Call['F']))
                $Call['F'] = 'Default';
            
            if (!isset($Call['G']))
                list($Call['G']) = array_reverse($N);

            // Загружаем контракт
            $Call = self::LoadContract($Call, $Mode);

            // Запоминание вызова
            // Защита от зацикливания.
            if ($Mode !== Code::Ring1)
                self::$_LastCall = $Call; // TODO: #refs48

            // Выбираем драйвер
            $Call = self::_DetermineDriver($Call);

            // FIXME Behaviour!
            // Фильтрация аргументов
            //if (self::_is('Filter', $Call['Contract']))
            //    $Call = self::_FilterCall($Call);
    
            // FIXME Behaviour!
            // Проверка аргументов
            //if (self::_is('Check', $Call['Contract']))
            //    self::_CheckCall($Call);



            // Устанавливаем текущее пространство имён
            self::SetNamespace($Call['N'], $Call['D']);

            // FIXME Behaviour!
            if (isset($Call['Contract']['PreferredNS']) && $Call['N'] !== $Call['Contract']['PreferredNS'])
                self::On('Code.Run.Namespace.NotPreferred', $Call);

            // Если функции нет, подгружаем код
            if (self::Fn($Call['F']) === null)
                self::_LoadSource($Call);

            // Хуки
            if (isset($Call['Contract']['Hook']['beforeRun']) && is_array($Call['Contract']['Hook']['beforeRun']))
                self::Run($Call['Contract']['Hook']['beforeRun'], Code::Ring1, 'Multi');
            
            // Выполняем!
            $Return = self::_Do($Call);

            self::$_Tree[] = array($Call['N'].':'.$Call['F'], self::$_Stack->count());

            // Хуки
            if (isset($Call['Contract']['Hook']['afterRun']))
                self::Run($Call['Contract']['Hook']['afterRun'], Code::Ring1, 'Multi');

            // FIXME Behaviour!
            // Возврат вызова как результата
            if (isset($Call['Contract']['Return']['Call']) && $Call['Contract']['Return']['Call'])
            {
                Code::On('Code.Return.Call.Detected', $Call);
                $Return = self::Run($Return);
            }
            
            // FIXME Behaviour!
            // Режим экономии
            if (self::_is('Economy', $Call['Contract']))
                self::Fn($Call['F'], null);

            // Фильтрация результата
            // FIXME Behaviour!
            /*
            if (self::_is('Filter', $Call['Contract']))
                if (isset($Call['Contract']['Return']['Filter']))
                    $Return = self::Run(
                        array(
                             'Calls' => array_merge(
                                 $Return,
                                 $Call['Contract']['Return']['Filter'])), Code::Ring1,
                        'Chain');

            // FIXME Behaviour!
            // Проверка результата

            if (!self::_CheckReturn($Call['Contract'], $Return))
                if (isset($Call['Contract']['Return']['Fallback']))
                    $Return = Core::Any($Call['Contract']['Return']['Fallback']);
*/
            self::$_Stack->pop();
            return $Return;
        }

        /**
         * @description Метод определения псевдонима для вызова.
         * @static
         * @param  $Call
         * @param  $Alias
         * @return
         */
        // FIXME Behaviour!
        public static function Alias ($Call, string $Alias)
        {
            if (self::isValidCall($Call))
                return self::$_Aliases[$Alias] = $Call;
            else
                self::On('Code.Alias.InvalidCall', $Alias);
        }

        // FIXME Behaviour!
        protected static function _Check($Data, $Contract, $Verbose = false)
        {
            $Decisions = self::Run (
                array(
                     'Prototype' =>
                        array(
                            'N' => 'Code.Checkers',
                            'F' => 'Check',
                            'Data'=> $Data,
                            'Contract'=>$Contract),
                     'Key'=> 'D',
                     'Values' => self::$_Conf['Checkers']),
                Code::Ring1, 'Revolver');

            if (in_array(false, $Decisions))
            {
                self::On('Code.errCodeCheckFailed', $Decisions);
                return false;
            }
            else
                return true;
        }

        // FIXME Behaviour!
        protected static function _CheckReturn($Contract, $Result)
        {
            if (isset($Contract['Return']))
                if (!self::_Check($Result, $Contract['Return']))
                    self::On('Code.WrongReturn',
                               array('Contract' => $Contract,
                                     'Result'   => $Result));
            return true;
        }

        protected static function _is($Key, $Contract)
        {
            if (isset($Contract[$Key]))
                return Core::Any($Contract[$Key]);
            else
                return false;
        }

        // FIXME Behaviour!
        protected static function _DetermineDriver($Call)
        {
            // Если в контракте указана политика драйверов, и драйвер не указан напрямую.
            
            if (!isset($Call['D']))
            {
                if (isset($Call['Contract']['Driver']))
                {
                    if (isset($Call['Contract']['Driver'][Environment]))
                        $Call['D'] = $Call['Contract']['Driver'][Environment];
                    else
                        $Call['D'] = $Call['Contract']['Driver']['Default'];
                }
                else
                    $Call['D'] = isset($Call['G']) ? $Call['G']: 'Default';
            }

            $Call['D'] = Core::Any($Call['D']);
            
            return $Call;
        }

        protected static function _FilterCall($Call)
        {
            if (isset($Call['Contract']['Arguments']))
                foreach ($Call['Contract']['Arguments'] as $Name => $Node)
                {
                    if (isset($Node['Filter']))
                        if (!isset($Call[$Name]))
                            $Call[$Name] = self::Run(
                                    array(
                                         'Calls' => array_merge($Call[$Name], $Node['Filter'])),
                                Code::Ring1, 'Chain');
                }
            
            return $Call;
        }

        protected static function _CheckCall($Call)
        {
            // Валидация
            if (isset($Call['Contract']['Arguments']))
                foreach ($Call['Contract']['Arguments'] as $Name => $Node)
                {
                    if (!isset($Call[$Name]))
                        self::_Check(null, $Node);
                    else
                        self::_Check($Call[$Name], $Node);
                }
        }

        public static function Trace($Index = null)
        {
            $StackArray = array();
            foreach (self::$_Stack as $Stack)
                $StackArray[] = $Stack;

            if ($Index == null)
                return $StackArray;
            elseif (isset($StackArray[$Index]))
                return $StackArray[$Index];
            else
                return null;
        }

        public static function Current($Call = null)
        {
            if (null !== $Call)
                return array_merge(self::$_Stack[0], $Call);
            else
                return self::$_Stack[0];
        }

        public static function Parent($Call = null)
        {
            return self::$_Stack[1];
        }
    }
