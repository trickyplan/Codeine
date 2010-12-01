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
    
    class Code extends Component
    {
        const Ring0    = 0;
        const Ring1    = 1;
        const Ring2    = 2;

        protected static $_Stack = array();
        protected static $_Conf;
        protected static $_Hooks     = array();
        protected static $_Contracts = array();
        protected static $_Functions = array();
        protected static $_Aliases   = array();

        protected static $_LastCall = null;
        protected static $_Registration = array();

        public static function Initialize ()
        {
            self::$_Stack = new SplStack();

            self::$_Conf  = self::_Configure(__CLASS__);
            self::On(__CLASS__, 'onInitialize');
        }

        public static function Conf($Key)
        {
            if (isset(self::$_Conf[$Key]))
                return self::$_Conf[$Key];
            else
            {
                Code::On(__CLASS__, 'errConfKeyNotFound', $Key);
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
        public static function On ($Class, $Event, $Data = array())
        {
            $Data['Class'] = $Class;
            $Data['Event'] = $Event;

            if (isset(self::$_Conf['Hooks'][$Class][$Event]))
            {
                if (self::$_Conf['Hooks'][$Class][$Event] !== null)
                    return Code::Run(
                        array(
                             'Calls' => self::$_Conf['Hooks'][$Class][$Event],
                            'Data'  => $Data
                        ), Code::Ring1, 'Feed');
                else
                    return null;
            }
            else
                return Code::Run(
                    array(
                         'Calls' => self::$_Conf['Hooks']['Default'],
                         'Data'  => $Data
                    ), Code::Ring1, 'Feed');
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
            self::On(__CLASS__, 'onShutdown');
        }

        /**
         * @description Проверяет, является ли аргумент корректным вызовом.
         * @static
         * @param  $Call
         * @return bool
         */
        public static function isValidCall($Call)
        {
            return is_array($Call) && isset($Call['F']);
        }

        /**
         * @description Загружает контракт.
         * @static
         * @param  $Namespace
         * @param  $Function
         * @param  $Driver
         * @return null
         */
        protected static function _LoadContract($Call, $Mode = Code::Ring2)
        {
            if (!isset(self::$_Contracts[$Call['Namespace']][$Call['Function']]))
            {
                // FIXME OPTME
                $Default = array($Call['Function'] => array());

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
                                            'ID' => $Call['Namespace'].'/'.$Call['D']
                                      )
                            ), Code::Ring1);
                    }
                    
                    $GroupContract =
                        Data::Read(
                            array('Point' => 'Contract',
                                  'Where' => array(
                                        'ID' => $Call['Namespace'].'/'.$Call['Group']
                                  )
                        ), Code::Ring1);
                        
                    $Contract = self::ConfWalk($GroupContract, $DriverContract);

                    if (isset($Contract[$Call['Function']]))
                        $Contract = $Contract[$Call['Function']];
                    else
                        $Contract = array();
                }
                else
                    $Contract = $Default;

                self::$_Contracts[$Call['Namespace']][$Call['Function']] = $Contract;
            }
            else
                $Contract = self::$_Contracts[$Call['Namespace']][$Call['Function']];

            if (isset($Call['Override']))
                $Contract =  self::ConfWalk($Contract, $Call['Override']);

            return $Contract;
        }

        /**
         * Метод роутинга
         * @static
         * @param  $Call
         * @return mixed|null
         */
        protected static function _Route ($Call)
        {
            // Для каждого определенного роутера...
            foreach (self::$_Conf['Routers'] as $Router)
            {
                // Пробуем роутер из списка...
                $NewCall = Code::Run(
                    array(
                        'F'=> 'Code/Routers::Route',
                        'D'=> $Router,
                        'Call' => $Call
                    ), Code::Ring1
                );

                // Если что-то получилось, то выходим из перебора
                if (self::isValidCall($NewCall))
                    break;
            }

            // Если хоть один роутер вернул результат...
            if ($NewCall === null)
                self::On(__CLASS__, 'errCodeRoutingFailed', $Call);
            else
                $Call = $NewCall;

            return $Call;
        }

        /**
         * Служебный метод, подготавливает переменные.
         * @static
         * @param  $Call
         * @return array
         */
        protected static function _Prepare ($Call)
        {
            if ($Call === null)
                self::On(__CLASS__, 'errCodePreparingFailed', $Call);
            // Ничего не помогло...

            list ($Call['F'], $Call['Function']) = explode('::', $Call['F']);
            $Call['Namespace'] = $Call['F'];
            
            list($Call['Group']) = array_reverse(explode('/', $Call['F']));

            return $Call;
        }

        protected static function _CheckDepends($Contract)
        {
            if (isset($Contract['Depends']))
            {
                if (isset($Contract['Depends']['External']))
                {
                    foreach ($Contract['Depends']['External'] as $Dependency)
                        if (!extension_loaded($Dependency))
                            self::On(__CLASS__, 'errExternalDependencyFailed', $Contract);
                }

                if (isset($Contract['Depends']['Internal']))
                {
                    foreach ($Contract['Depends']['Internal'] as $Dependency)
                        if (self::Run(array('F' => $Dependency), Code::Ring1, 'Test'))
                            self::On(__CLASS__, 'errInternalDependencyFailed', $Contract);
                }
            }
        }

        protected static function SetNamespace($Namespace, $Driver)
        {
            return self::$_Registration = array('Namespace' => $Namespace, 'Driver'=> $Driver);
        }

        public static function Fn($Function, $Code = null)
        {
            if (null !== $Code)
            {
                if (false !== $Code)
                    self::$_Functions
                        [self::$_Registration['Namespace']]
                            [self::$_Registration['Driver']][$Function] = $Code;
                else
                    unset(self::$_Functions
                        [self::$_Registration['Namespace']]
                            [self::$_Registration['Driver']][$Function]);
            }
            else
                if (isset(self::$_Functions
                        [self::$_Registration['Namespace']]
                            [self::$_Registration['Driver']][$Function]))
                return self::$_Functions
                            [self::$_Registration['Namespace']]
                                [self::$_Registration['Driver']][$Function];
        }

        protected static function _LoadSource($Call, $Contract)
        {
            if (!isset($Contract['Engine']))
                $Contract['Engine'] = 'Include';

            switch ($Contract['Engine'])
            {
                case 'Include':
                    if (isset($Contract['Source']))
                        $Filename = Data::Locate('Code', $Contract['Source']);
                    else
                        $Filename = Data::Locate('Code', $Call['Namespace'].'/'.$Call['D'].'.php');
                    
                    if ($Filename)
                        return (include $Filename);
                    else
                        self::On(__CLASS__, 'errCodeDriverFileNotFound', $Call);
                break;

                case 'Code':
                    if(isset($Contract['Source']))
                        eval('self::Fn(\''.$Call['Function'].'\',
                            function ($Call) {'.$Contract['Source'].'});');
                    else
                        Code::On(__CLASS__, 'errCodeSourceInContractNotSpecified', $Contract);
                break;

                case 'Data':
                    if(isset($Contract['Source']))
                        eval('self::Fn(\''.$Call['Function'].'\',
                            function ($Call) {'.Data::Read('Source', $Contract['Source']).'});');
                        // TODO Test!
                    else
                        Code::On(__CLASS__, 'errCodeSourceDataInContractNotSpecified', $Contract);
                break;

                default:
                    Code::On(__CLASS__, 'errCodeContractUnknownEngine', $Contract['Engine']);
                break;
            }
        }

        protected static function _Do($Call)
        {
            $F = self::Fn($Call['Function']);

            if (null == $F)
            {
                self::On(__CLASS__,'errCodeFunctionIsNotCallable', $Call);
                var_dump($Call);
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

            if ($Runner !== null)
            {
                self::$_Stack->pop();
                return self::Run(
                    array(
                         'F' => 'Code/Runners/'.$Runner.'::Run',
                         'Call' => $Call,
                         'Mode' => $Mode
                          ), $Mode);
            }

            if ($Executor !== null)
            {
                self::$_Stack->pop();
                return self::Run(
                    array(
                         'F' => 'Code/Executors/'.$Executor.'::Run',
                         'Call' => $Call,
                         'Mode' => $Mode
                          ), $Mode);
            }
            
            $Return = null;

            // Проверка на псевдонимы. Псевдонимы проверяются при определении.

            if (is_string($Call) && isset(self::$_Aliases[$Call]))
                $Call = self::$_Aliases[$Call];
            elseif (!self::isValidCall($Call))
                $Call = self::_Route($Call);

            // Если передан не готовый объект запроса, а что-то иное, вызываем роутинг.

            // Запоминание вызова
            if ($Mode !== Code::Ring1) // Защита от зацикливания.
                self::$_LastCall = $Call; // TODO: #refs48

            // Готовим удобные переменные
            $Call = self::_Prepare($Call);
            
            // Загружаем контракт
            $Contract = self::_LoadContract($Call, $Mode);

            if ($Mode == Code::Ring2)
            {
                self::On(__CLASS__,
                       'beforeRun',
                       array(
                            'Contract'  => $Contract,
                            'Call'      => $Call));
            }

            // Выбираем драйвер
            $Call = self::_DetermineDriver($Contract, $Call);

            // Фильтрация аргументов
            if (self::_is('Filter', $Contract))
                $Call = self::_FilterCall($Contract, $Call);
            
            // Проверка аргументов
            if (self::_is('Check', $Contract))
                self::_CheckCall($Contract, $Call);

            // Хуки
            if (isset($Contract['Hook']['beforeRun']))
                self::Run($Contract['Hook']['beforeRun'], Code::Ring1, 'Multi');

            // Устанавливаем текущее пространство имён
            self::SetNamespace($Call['Namespace'], $Call['D']);

            // Если функции нет, подгружаем код
            if (self::Fn($Call['Function']) === null)
            {
                self::_CheckDepends($Contract);
                self::_LoadSource($Call, $Contract);
            }

            // Выполняем!
            $Return = self::_Do($Call);

            // Хуки
            if (isset($Contract['Hook']['afterRun']))
                self::Run($Contract['Hook']['afterRun'], Code::Ring1, 'Multi');

            // Возврат вызова как результата
            if (isset($Contract['Return']['Call']) && $Contract['Return']['Call'])
                $Return = self::Run($Return);

            // Режим экономии
            if (self::_is('Economy', $Contract))
                self::Fn($Call['Function'], null);

            // Фильтрация результата

            if (self::_is('Filter', $Contract))
                if (isset($Contract['Return']['Filter']))
                    $Return = self::Run(
                        array(
                             'Calls' => array_merge(
                                 $Return,
                                 $Contract['Return']['Filter'])), Code::Ring1,
                        'Chain');

            // Проверка результата

            if (!self::_CheckReturn($Contract, $Return))
                if (isset($Contract['Return']['Fallback']))
                    $Return = Core::Any($Contract['Return']['Fallback']);

            if ($Mode !== Code::Ring1)
                self::On(__CLASS__,
                       'afterRun',
                       array(
                            'Contract'  => $Contract,
                            'Call'      => $Call,
                            'Return'    => $Return));

            self::$_Stack->pop();
            return $Return;
        }

        public static function LastCall($Call = array(), $Mode = Code::Ring2)
        {
            if (null !== self::$_LastCall)
            {
                $Call['Repeated'] = true;
                return self::Run(self::ConfWalk(self::$_LastCall, $Call), $Mode);
            }
            else
                return null;
        }

        /**
         * @description Метод определения псевдонима для вызова.
         * @static
         * @throws WTF
         * @param  $Call
         * @param  $Alias
         * @return
         */
        public static function Alias ($Call, string $Alias)
        {
            if (self::isValidCall($Call))
                return self::$_Aliases[$Alias] = $Call;
            else
                self::On(__CLASS__, 'errCodeInvalidCallInAlias', $Alias);
        }

        protected static function _Check($Data, $Contract, $Verbose = false)
        {
            $Decisions = self::Run (
                array(
                     'Prototype' =>
                        array(
                            'F'=>'Code/Checkers::Check',
                            'Data'=> $Data,
                            'Contract'=>$Contract),
                     'Key'=> 'D',
                     'Values' => self::$_Conf['Checkers']),
                Code::Ring1, 'Revolver');

            if (in_array(false, $Decisions))
            {
                self::On(__CLASS__, 'errCodeCheckFailed', $Decisions);
                return false;
            }
            else
                return true;
        }

        protected static function _CheckReturn($Contract, $Result)
        {
            if (isset($Contract['Return']))
                if (!self::_Check($Result, $Contract['Return']))
                    self::On(__CLASS__, 'WrongReturn',
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

        protected static function _DetermineDriver($Contract, $Call)
        {
            // Если в контракте указана политика драйверов, и драйвер не указан напрямую.
            
            if (!isset($Call['D']))
            {
                if (isset($Contract['Driver']))
                {
                    if (isset($Contract['Driver'][Environment]))
                        $Call['D'] = $Contract['Driver'][Environment];
                    else
                        $Call['D'] = $Contract['Driver']['Default'];
                }
                else
                    $Call['D'] = $Call['Group'];
            }

            $Call['D'] = Core::Any($Call['D']);
            return $Call;
        }

        protected static function _FilterCall($Contract, $Call)
        {
            if (isset($Contract['Arguments']))
                foreach ($Contract['Arguments'] as $Name => $ContractNode)
                {
                    if (isset($ContractNode['Filter']))
                        if (!isset($Call[$Name]))
                            $Call[$Name] = self::Run(
                                    array(
                                         'Calls' => array_merge($Call[$Name], $ContractNode['Filter'])),
                                Code::Ring1, 'Chain');
                }
            
            return $Call;
        }

        protected static function _CheckCall($Contract, $Call)
        {
            // Валидация
            if (isset($Contract['Arguments']))
                foreach ($Contract['Arguments'] as $Name => $ContractNode)
                {
                    if (!isset($Call[$Name]))
                        self::_Check(null, $ContractNode);
                    else
                        self::_Check($Call[$Name], $ContractNode);
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

        public static function ParentCall()
        {
            return self::$_Stack[1];
        }
    }