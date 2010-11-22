<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Core Module
     * @description: NextGen Code Module
     * @package Core
     * @subpackage Code
     * @version 0.1
     * @date 28.10.10
     * @time 2:16
     */
    
    class Code extends Component
    {
        const Internal = true;
        protected static $_Conf;
        protected static $_Hooks;
        protected static $_Contracts = array();
        protected static $_Functions = array();
        protected static $_Aliases   = array();

        protected static $_LastCall = null;

        protected static $_Registration = array();

        public static function GetInterfaces()
        {
            return self::$_Conf['Interfaces'];
        }

        public static function Hook ($Class, $Event)
        {
            if (isset(self::$_Hooks[$Class][$Event]))
                return Code::Multi(self::$_Hooks[$Class][$Event]);
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
            self::$_Conf = self::_Configure(__CLASS__);
            self::$_Hooks = self::_Configure('Hooks');
            self::Hook(__CLASS__, 'onInitialize');
        }

        public static function Shutdown ()
        {
            self::Hook(__CLASS__, 'onShutdown');
            // TODO: Implement Shutdown() method.
        }

        /**
         * @description Если аргумент является вызовом, возвращает результат его исполнения. Если аргумент - переменная, то возвращает её.
         * @static
         * @param  $Call
         * @return mixed
         */
        public static function CallOrValue($Call)
        {
            if (self::isValidCall($Call))
                return self::Run($Call,Code::Internal);
            else
                return $Call;
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
                        'F'=> 'System/Route/Route',
                        'D'=> $Router,
                        'Call' => $Call
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
                throw new WTF('404 Router');

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
                throw new WTF('404 Prepare '); // FIXME: Normal Exception
            // Ничего не помогло

            $Slices = explode('/', $Call['F']);
            $Call['Namespace'] = implode('/', array_slice($Slices, 0, (count($Slices)-1)));
            list($Call['Function'], $Call['Group']) = array_reverse($Slices);

            return $Call;
        }

        protected static function SetNamespace($Namespace, $Driver)
        {
            self::$_Registration = array('Namespace' => $Namespace, 'Driver'=> $Driver);
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
            
            return null;
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
                        throw new WTF($Call['Namespace'].'/'.$Call['D'].'.php');
                break;

                case 'Code':
                    if(isset($Contract['Source']))
                        eval('self::Fn(\''.$Call['Function'].'\',
                            function ($Call) {'.$Contract['Source'].'});');
                break;

                case 'Data':
                    if(isset($Contract['Source']))
                        eval('self::Fn(\''.$Call['Function'].'\',
                            function ($Call) {'.Data::Read('Source', $Contract['Source']).'});');
                        // TODO Test!
                break;

                default:
                    throw new WTF('Unknow Engine '.$Contract['Engine']);
                break;
            }
        }

        protected static function _Do($Call)
        {
            try
            {
                $F = self::Fn($Call['Function']);

                if (null == $F)
                    $F = 'var_dump';

                if (is_callable($F) or ($F instanceof Closure))
                    return $F($Call);
            }
            catch (Exception $e)
            {
                echo $e->getMessage(); // TODO FIXME;
            }
        }

        /**
         * @description Главный метод Codeine, запускает код.
         * @param  $Call - запрос, ассоциативный массив или совместимый аргумент
         * @return mixed $Result - результат
         */
        
        public static function Run ($Call, $Mode = false)
        {
            $Return = null;

            // Проверка на псевдонимы. Псевдонимы проверяются при определении.
            if (is_string($Call) && isset(self::$_Aliases[$Call]))
                $Call = self::$_Aliases[$Call];
            elseif (!self::isValidCall($Call))
                $Call = self::_Route($Call);
            // Если передан не готовый объект запроса, а что-то иное, вызываем роутинг.

            // Запоминание вызова
            if ($Mode !== Code::Internal) // Защита от зацикливания.
                self::$_LastCall = $Call;

            // Готовим удобные переменные
            $Call = self::_Prepare($Call);
            
            // Загружаем контракт
            $Contract = self::_LoadContract($Call);

            // Закешировано?
            if (($Cached = self::_CacheBefore($Contract, $Call)) !== null)
                return $Cached;

            // Если отложенный вызов, возвращаемся сразу.
            if (self::_isDeferred($Call))
                return Code::Defer($Call);

            // Выбираем драйвер
            $Call = self::_DetermineDriver($Contract, $Call);

            // Фильтрация аргументов
            $Call = self::_FilterCall($Contract, $Call);
            
            // Проверка аргументов
            self::_CheckCall($Contract, $Call);

            // Хуки
            if (isset($Contract['Hook']['Before']))
                self::Multi($Contract['Hook']['Before'], Code::Internal);

            // Устанавливаем текущее пространство имён
            self::SetNamespace($Call['Namespace'], $Call['D']);

            // Если функции нет, подгружаем код
            if (self::Fn($Call['Function']) === null)
                self::_LoadSource($Call, $Contract);

            $Return = self::_Do($Call);

            // Хуки
            if (isset($Contract['Hook']['After']))
                self::Multi($Contract['Hook']['After'], Code::Internal);

            // Коллбеки
            if (isset($Contract['Return']['Call']) && $Contract['Return']['Call'])
                $Return = self::Run($Return);

            // Проверка результата
            self::_CheckReturn($Contract, $Return);

            // Кеширование
            self::_CacheAfter($Contract, $Call, $Return);

            return $Return;
        }

        public static function LastCall($Call = array(), $Mode = null)
        {
            if (null !== self::$_LastCall)
                return self::Run(self::ConfWalk(self::$_LastCall, $Call), $Mode);
        }

        public static function Dump($Call, $Driver = null)
        {
            return self::Chain($Call,
                array(array('F' => 'System/Dump/Variable')));
        }

        public static function Revolver ($Call, $Key, $Values, $Mode)
        {
            $Output = array();

            foreach ($Values as $Value)
                $Output[$Value] = self::Run(self::ConfWalk($Call, array($Key => $Value)), $Mode);

            return $Output;
        }

        public static function Batch ($Call, $Sets, $Mode = null)
        {
            $Output = array();
            
            foreach ($Sets as $IX => $Set)
                $Output[$IX] = self::Run(self::ConfWalk($Call, $Set), $Mode);

            return $Output;
        }

        public static function Defer ($Call)
        {
            // TODO: Deffered run
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
        public static function Alias ($Call, $Alias)
        {
            if (self::isValidCall($Call))
                return self::$_Aliases[$Alias] = $Call;
            else
                throw new WTF('Invalid Call');
        }

        public static function Chain($FirstCall, $Calls, $Mode = false)
        {
            $Result = self::Run($FirstCall);

            foreach ($Calls as $Call)
            {
                $Call['Input'] = $Result;
                $Result = Code::Run($Call, $Mode);
            }

            return $Result;
        }

        public static function Multi($Calls, $Mode = false)
        {
            $Result = array();

            if (is_array($Calls))
                foreach ($Calls as $Index => $Call)
                    $Result[$Index] = Code::Run($Call, false);
                else
                    $Result = null;

            return $Result;
        }

        public static function Test ($Call, $Suite = null)
        {
            if (!self::isValidCall($Call))
                $Call = self::_Route($Call);

             // Готовим удобные переменные
            $Call = self::_Prepare($Call);

            // Загружаем контракт
            $Contract = self::_LoadContract($Call);

            $Call = self::_DetermineDriver($Contract, $Call);

            if (!isset($Contract['Test']))
                return null;

            // TODO: Profiling analyze!!!
            
            if ($Suite == null)
            {
                $Decision = true;

                foreach ($Contract['Test'] as $Name => $Suite)
                {
                    $Return = self::Run(array_merge($Suite['Arguments'], $Call));
                    
                    if (isset($Suite['Return'])
                        && !self::_Check(array('Value' => $Return), $Suite['Return']))
                            $Decision = false;
                }
            }
            
            return $Decision;
        }

        protected static function _Check($Data, $Contract, $Verbose = false)
        {
            foreach (self::$_Conf['Checkers'] as $Checker)
            {
                $Decisions[$Checker] = Code::Run (
                    array(
                         'F'=>'Meta/Checkers/Check',
                         'D'=>$Checker,
                         'Data'=>$Data,
                         'Contract'=>$Contract), Code::Internal);
            }

            if ($Verbose)
                return $Decisions;
            else
                return !in_array(false, $Decisions);
        }

        protected static function _CheckReturn($Contract, $Result)
        {
            if (isset($Contract['Return']))
            {
                if (isset($Contract['Return']['Filter']))
                    $Result = self::Chain($Result, $Contract['Return']['Filter'], Code::Internal);

                if (!self::_Check($Result, $Contract['Return']))
                {
                    if (isset($Contract['Return']['Fallback']))
                        $Result = self::CallOrValue($Contract['Return']['Fallback']);
                    else
                        throw new WTF('Wrong return');
                }
            }
        }

        protected static function _CacheBefore($Contract, $Call)
        {
            // Кэширование
            if (isset($Contract['Cache']))
            {
                $Contract['Cache'] = self::CallOrValue($Contract['Cache']);
                if ($Contract['Cache'] and (($Cached = self::_CacheGet($Call)) != null))
                    return $Cached;
            }

            return null;
        }

        protected static function _CacheAfter($Contract, $Call, $Result)
        {
            if ($Contract['Cache'])
                self::_CacheSet($Call, $Result);
        }

        protected static function _isDeferred($Contract)
        {
            if (isset($Contract['Deferred']))
                {
                    $Contract['Deferred'] = self::CallOrValue($Contract['Deferred']);
                    if ($Contract['Deferred'])
                        return true;
                }
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

                    $Call['D'] = self::CallOrValue($Call['D']);
                }
                else
                    $Call['D'] = $Call['Group'];
            }
            else
                $Call['D'] = self::CallOrValue($Call['D']);

            return $Call;
        }

        protected static function _FilterCall($Contract, $Call)
        {
            if (isset($Contract['Arguments']))
                foreach ($Contract['Arguments'] as $Name => $ContractNode)
                {
                    if (isset($ContractNode['Filter']))
                        if (!isset($Call[$Name]))
                            $Call[$Name] = self::Chain($Call[$Name], $ContractNode['Filter'],
                                Code::Internal);
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

        protected static function _CacheGet($Call)
        {

        }

        protected static function _CacheSet($Call, $Value)
        {

        }
    }