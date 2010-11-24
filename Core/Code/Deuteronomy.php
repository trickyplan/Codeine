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
        const Internal = true;
        const Normal = 0;
        
        protected static $_Conf;
        protected static $_Hooks     = array();
        protected static $_Contracts = array();
        protected static $_Functions = array();
        protected static $_Aliases   = array();

        protected static $_LastCall = null;
        protected static $_Registration = array();

        public static function Conf($Key)
        {
            if (isset(self::$_Conf[$Key]))
                return self::$_Conf[$Key];
            else
            {
                Code::Hook(__CLASS__, 'errConfKeyNotFound', $Key);
                return null;
            }
        }

        public static function Hook ($Class, $Event, $Data = array())
        {
            $Data = array('Class' => $Class, 'Event' => $Event);
            
            if (isset(self::$_Hooks[$Class][$Event]))
                return Code::Run(
                    array(
                         'Calls' => self::$_Hooks[$Class][$Event],
                         'Data'  => $Data
                    ), Code::Internal, 'Feed');
            else
                return null;
        }

        public static function AddHook ($Class, $Event, $ID, $Call)
        {
            return self::$_Hooks[$Class][$Event][$ID] = $Call;
        }

        public static function DelHook ($Class, $Event, $ID)
        {
            unset (self::$_Hooks[$Class][$Event][$ID]);
        }

        public static function Initialize ()
        {
            self::$_Conf  = self::_Configure(__CLASS__);
            self::$_Hooks = self::_Configure('Hooks');
            self::Hook(__CLASS__, 'onInitialize');
        }

        public static function Shutdown ()
        {
            self::Hook(__CLASS__, 'onShutdown');
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
        protected static function _LoadContract($Call)
        {
            if (!isset(self::$_Contracts[$Call['Namespace']][$Call['Function']]))
            {
                $Default = array(
                    $Call['Function'] => json_decode(
                        file_get_contents (Data::Locate('Code', 'Default.json')), true)
                );

                if (isset($Call['D']) &&
                    $ContractFN = Data::Locate('Code', $Call['Namespace'].'/'.$Call['D'].'.json'))
                        $DriverContract   = json_decode(file_get_contents ($ContractFN), true);
                else
                    $DriverContract   = array();

                if ($NSContractFN = Data::Locate('Code', $Call['Namespace'].'/'.$Call['Group'].'.json'))
                    $NSContract = json_decode(file_get_contents ($NSContractFN),true);
                else
                    $NSContract = array();

                $Contract = self::ConfWalk(self::ConfWalk($Default, $NSContract), $DriverContract);
                $Contract = $Contract[$Call['Function']];

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
                        'F'=> 'Code/Routers/Route',
                        'D'=> $Router,
                        'Call' => $Call
                    ), Code::Internal
                );

                // Если что-то получилось, то выходим из перебора
                if (self::isValidCall($NewCall))
                    break;
            }

            // Если хоть один роутер вернул результат...
            if ($NewCall === null)
                self::Hook(__CLASS__, 'errCodeRoutingFailed', $Call);
            else
                $Call = $NewCall;

            return $Call;
        }

        /**
         * Служебный метод, подготавливает переменные.
         * @static
         * @throws WTF
         * @param  $Call
         * @return array
         */
        protected static function _Prepare ($Call)
        {
            if ($Call === null)
                self::Hook(__CLASS__, 'errCodePreparingFailed', $Call);
            // Ничего не помогло...

            $Slices = explode('/', $Call['F']);
            $Call['Namespace'] = implode('/', array_slice($Slices, 0, (count($Slices)-1)));
            list($Call['Function'], $Call['Group']) = array_reverse($Slices);

            return $Call;
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
                        self::Hook(__CLASS__, 'errCodeDriverFileNotFound', $Call);
                break;

                case 'Code':
                    if(isset($Contract['Source']))
                        eval('self::Fn(\''.$Call['Function'].'\',
                            function ($Call) {'.$Contract['Source'].'});');
                    else
                        Code::Hook(__CLASS__, 'errCodeSourceInContractNotSpecified', $Contract);
                break;

                case 'Data':
                    if(isset($Contract['Source']))
                        eval('self::Fn(\''.$Call['Function'].'\',
                            function ($Call) {'.Data::Read('Source', $Contract['Source']).'});');
                        // TODO Test!
                    else
                        Code::Hook(__CLASS__, 'errCodeSourceDataInContractNotSpecified', $Contract);
                break;

                default:
                    Code::Hook(__CLASS__, 'errCodeContractUnknownEngine', $Contract['Engine']);
                break;
            }
        }

        protected static function _Do($Call)
        {
            $F = self::Fn($Call['Function']);

            if (null == $F)
                self::Hook(__CLASS__,'errCodeFunctionIsNotCallable', $Call);
            elseif (is_callable($F) or ($F instanceof Closure))
                return $F($Call);
        }

        /**
         * @description Главный метод Codeine, запускает код.
         * @param  $Call - запрос, ассоциативный массив или совместимый аргумент
         * @return mixed $Result - результат
         */
        
        public static function Run ($Call, $Mode = Code::Normal, $Runner = null, $Executor = null)
        {
            if ($Runner !== null)
                return self::Run(
                    array(
                         'F' => 'Code/Runners/'.$Runner.'/Run',
                         'Call' => $Call,
                         'Mode' => $Mode
                          ), $Mode);

            if ($Executor !== null)
                return self::Run(
                    array(
                         'F' => 'Code/Executors/'.$Executor.'/Run',
                         'Call' => $Call,
                         'Mode' => $Mode
                          ), $Mode);

            $Return = null;

            // Проверка на псевдонимы. Псевдонимы проверяются при определении.

            if (is_string($Call) && isset(self::$_Aliases[$Call]))
                $Call = self::$_Aliases[$Call];
            elseif (!self::isValidCall($Call))
                $Call = self::_Route($Call);

            // Если передан не готовый объект запроса, а что-то иное, вызываем роутинг.

            // Запоминание вызова
            if ($Mode !== Code::Internal) // Защита от зацикливания.
                self::$_LastCall = $Call; // TODO: #refs48

            // Готовим удобные переменные
            $Call = self::_Prepare($Call);
            
            // Загружаем контракт
            $Contract = self::_LoadContract($Call);

            if ($Mode !== Code::Internal)
            {
                self::Hook(__CLASS__,
                       'beforeRun',
                       array(
                            'Contract'  => $Contract,
                            'Call'      => $Call));

                // Если отложенный вызов, возвращаемся сразу.
                if (self::_is('Deferred', $Contract))
                    return Code::Run($Call, Code::Internal, $Runner, 'Deferred');

                if (self::_is('Cache', $Contract))
                    return Code::Run($Call, Code::Internal, $Runner, 'Cache');
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
                self::Run($Contract['Hook']['beforeRun'], Code::Internal, 'Multi');

            // Устанавливаем текущее пространство имён
            self::SetNamespace($Call['Namespace'], $Call['D']);

            // Если функции нет, подгружаем код
            if (self::Fn($Call['Function']) === null)
                self::_LoadSource($Call, $Contract);

            // Выполняем!
            $Return = self::_Do($Call);

            // Хуки
            if (isset($Contract['Hook']['afterRun']))
                self::Run($Contract['Hook']['afterRun'], Code::Internal, 'Multi');

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
                             'Calls' => array_merge($Return, $Contract['Return']['Filter'])), Code::Internal,
                        'Chain');

            // Проверка результата

            if (!self::_CheckReturn($Contract, $Return))
                if (isset($Contract['Return']['Fallback']))
                    $Return = Core::Any($Contract['Return']['Fallback']);

            if ($Mode !== Code::Internal)
                self::Hook(__CLASS__,
                       'afterRun',
                       array(
                            'Contract'  => $Contract,
                            'Call'      => $Call,
                            'Return'    => $Return));

            return $Return;
        }

        public static function LastCall($Call = array(), $Mode = Code::Normal)
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
                Code::Hook(__CLASS__, 'errCodeInvalidCallInAlias', $Alias);
        }

        protected static function _Check($Data, $Contract, $Verbose = false)
        {
            $Decisions = Code::Run (
                array(
                     'Prototype' =>
                        array(
                            'F'=>'Code/Checkers/Check',
                            'Data'=> $Data,
                            'Contract'=>$Contract),
                     'Key'=> 'D',
                     'Values' => self::$_Conf['Checkers']),
                Code::Internal, 'Revolver');

            if (in_array(false, $Decisions))
            {
                Code::Hook(__CLASS__, 'errCodeCheckFailed', $Decisions);
                return false;
            }
            else
                return true;
        }

        protected static function _CheckReturn($Contract, $Result)
        {
            if (isset($Contract['Return']))
                if (!self::_Check($Result, $Contract['Return']))
                    Code::Hook(__CLASS__, 'WrongReturn',
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
                    if (isset($_ENV['WorkStyle'])
                        && isset($Contract['Driver'][$_ENV['WorkStyle']]))
                            $Call['D'] = $Contract['Driver'][$_ENV['WorkStyle']];
                    else
                        $Call['D'] = $Contract['Driver']['Default'];

                    $Call['D'] = Core::Any($Call['D']);
                }
                else
                    $Call['D'] = $Call['Group'];
            }
            else
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
                                Code::Internal, 'Chain');
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
    }