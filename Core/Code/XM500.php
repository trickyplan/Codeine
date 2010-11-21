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
        protected static $_Conf;
        protected static $_Contracts = array();
        protected static $_Functions = array();
        protected static $_Aliases   = array();

        public static function GetInterfaces()
        {
            return self::$_Conf['Interfaces'];
        }
        
        public static function Initialize ()
        {
            self::$_Conf = self::_Configure(__CLASS__);
        }

        public static function Shutdown ()
        {
            // TODO: Implement Shutdown() method.
        }

        private static function _LoadContract($Namespace, $Function, $Driver)
        {
            list($NamespaceLast) = array_reverse(explode('/', $Namespace));

            $NSContractFN = Data::Locate('Code', $Namespace.'/'.$NamespaceLast.'.json');
            $ContractFN   = Data::Locate('Code', $Namespace.'/'.$Driver.'.json');

            $Contract   = array();
            $NSContract = array();

            if ($ContractFN)
                $Contract   = json_decode(file_get_contents ($ContractFN), true);

            if ($NSContractFN)
            {
                $NSContract = json_decode(file_get_contents ($NSContractFN),true);
                self::$_Contracts[$Namespace] = Component::ConfWalk($NSContract, $Contract);
            }
            else
                self::$_Contracts[$Namespace] = $Contract;

            if (isset(self::$_Contracts[$Namespace][$Function]))
                return self::$_Contracts[$Namespace][$Function];
            else
                return null;
        }

        public static function isValidCall($Call)
        {
            return is_array($Call) && isset($Call['F']);
        }

        private static function _Route ($Call)
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
                    )
                );

                // Если что-то получилось, то выходим из перебора
                if (self::isValidCall($NewCall))
                    break;
            }

            // Если хоть один роутер вернул результат...
            if ($NewCall !== null)
                $Call = $NewCall;
            else
                $Call = null;

            return $Call;
        }

        private static function _Prepare ($Call)
        {
            if ($Call === null)
                throw new WTF('404', var_dump($Call));
            // Ничего не помогло

            $Slices = explode('/', $Call['F']);

            $NSF = $Call['F'];

            $Namespace = implode('/',array_slice($Slices, 0, (count($Slices)-1)));
            
            $Function = $Slices[count($Slices)-1];

            if (isset($Call['D']))
                $Driver = $Call['D'];
            else
                if (isset($Slices[count($Slices)-2]))
                    $Driver = $Slices[count($Slices)-2];
                else
                    $Driver = 'Default';

            return array(
                $NSF,
                $Namespace,
                $Function,
                $Driver);
        }

        /**
         * @description Главный метод Codeine, запускает код.
         * @param  $Call - запрос, ассоциативный массив или совместимый аргумент
         * @return mixed $Result - результат
         */
        
        public static function Run ($Call)
        {
            // Проверка на псевдонимы
            if (is_string($Call) && isset(self::$_Aliases[$Call]))
                $Call = self::$_Aliases[$Call];
            
            // Если передан не готовый объект запроса, а что-то иное, вызываем роутинг

            if (!self::isValidCall($Call))
                $Call = self::_Route($Call);

            // Готовим удобные переменные
            list($NSF, $Namespace, $Function, $Driver) = self::_Prepare($Call);            

            list($Call['Plugin']) = array_reverse(explode('/', $Call['F']));
            
            $Result = null;
           
            if (($Contract = self::_LoadContract($Namespace, $Function, $Driver)) !== null)
            {
                // Возможность переопределять элементы контракта
                if (isset($Call['Override']))
                    $Contract = Component::ConfWalk($Contract, $Call['Override']);

                // Кэширование
                if (isset($Contract['Cache']))
                {
                    $Contract['Cache'] = self::CallOrValue($Contract['Cache']);
                    if ($Contract['Cache'] and (($Cached = self::_CacheGet($Call)) != null))
                        return $Cached;
                }

                if (isset($Contract['Deferred']))
                {
                    $Contract['Deferred'] = self::CallOrValue($Contract['Deferred']);
                    if ($Contract['Deferred'])
                    {
                        return Code::Defer($Call);
                    };
                }

                // Если в контракте указана политика драйверов, и драйвер не указан напрямую.
                if (!isset($Call['D']) && isset($Contract['Driver']))
                {
                    if (isset($_ENV['WorkStyle'])
                        && isset($Contract['Driver'][$_ENV['WorkStyle']]))
                            $Driver = $Contract['Driver'][$_ENV['WorkStyle']];
                    else
                        $Driver = $Contract['Driver']['Default'];

                    $Driver = self::CallOrValue($Driver);
                }

                // Валидация и валидация аргументов
                foreach ($Contract['Arguments'] as $Name => $ContractNode)
                {
                    if (!isset($Call[$Name]))
                        self::_Check(null, $ContractNode);
                    else
                    {
                        if (isset($ContractNode['Filter']))
                            foreach ($ContractNode['Filter'] as $Filter)
                                $Call[$Name] = self::Run (
                                    array('F' => 'Data/Filters/Filter',
                                          'D' => $Filter,
                                          'Input' => $Call[$Name]));

                        self::_Check($Call[$Name], $ContractNode);
                    }
                }


                // Хуки
                if (isset($Contract['Hook']['Before']))
                    foreach ($Contract['Hook']['Before'] as $Hook)
                        self::Run($Hook);
            }

            // FIXME MultiF loading
            if (!isset(self::$_Functions[$NSF]))
            {
                $DriverName = Data::Locate('Code', $Namespace.'/'.$Driver.'.php');
                if ($DriverName)
                {
                    include $DriverName;
                    self::$_Functions[$NSF] = $$Function;
                }
                else
                    throw new WTF(var_export($Call));
            }
           
            $F = self::$_Functions[$NSF];          

            try
            {
                if (is_callable($F) or $F instanceof Closure)
                    $Result = $F($Call);
            }
            catch (Exception $e)
            {
                echo $e->getMessage(); // TODO FIXME;
            }

            if ($Contract)
            {
                if ($Contract['Cache'])
                    self::_CacheSet($Call, $Result);
                
                if (isset($Contract['Hook']['After']))
                    foreach ($Contract['Hook']['After'] as $Hook)
                        self::Run ($Hook);

                if (isset($Contract['Return']))
                {
                    if (isset($Contract['Return']['Filter']))
                        foreach ($Contract['Return']['Filter'] as $Filter)
                            $Result = self::Run (
                                array('F' => 'Data/Filters/Filter',
                                      'D' => $Filter,
                                      'Input' => $Result));

                    if (!self::_Check($Result, $Contract['Return']))
                    {
                        if (isset($Contract['Return']['Fallback']))
                            $Result = self::CallOrValue($Contract['Return']['Fallback']);
                        else
                            throw new WTF('Wrong return');
                    }
                }
            }

            return $Result;
        }

        public static function Defer ($Call)
        {
            // TODO: Deffered run
            return null;
        }

        public static function Alias ($Call, $Alias)
        {
            return self::$_Aliases[$Alias] = $Call;
        }

        public static function Chain()
        {
            $Calls = func_get_args();

            $Result = null;
            
            foreach ($Calls as $Call)
            {
                $Call['Input'] = $Result;
                $Result = Code::Run($Call);
            }

            return $Result;
        }

        public static function Multi()
        {
            $Calls = func_get_args();
            $Result = array();

            if (is_array($Calls))
                foreach ($Calls as $Index => $Call)
                    $Result[$Index] = Code::Run($Call);
                else
                    $Result = null;

            return $Result;
        }


        public static function Test ($Call, $Suite = null)
        {
            if (!self::isValidCall($Call))
                $Call = self::_Route($Call);
            
            list($NSF, $Namespace, $Function, $Driver) = self::_Prepare($Call);

            if (($Contract = self::_LoadContract($Namespace, $Function, $Driver)) !== null)
            {
                if ($Suite == null)
                {
                    $Decision = true;

                    foreach ($Contract['Test'] as $Name => $Suite)
                    {
                        $TestCall = array_merge($Suite['Arguments'], $Call);
                        $Result = self::Run($TestCall);

                        if (isset($Suite['Return']['Value']))
                            if ($Result != self::CallOrValue($Suite['Return']['Value']))
                                $Decision = false;

                        if (isset($Suite['Return'])
                            && !self::_Check(array('Value'=>$Result), $Suite['Return']))
                                $Decision = false;
                    }
                }
                return $Decision;
            } 
            else
                return null;
        }

        public static function CallOrValue($Call)
        {
            if (self::isValidCall($Call))
                return self::Run($Call);
            else
                return $Call;
        }

        private static function _Check($Data, $Contract)
        {
            foreach (self::$_Conf['Checkers'] as $Checker)
            {
                $Checked = Code::Run
                    (array('F'=>'Meta/Checkers/Check','D'=>$Checker, 'Data'=>$Data, 'Contract'=>$Contract));

                if (!$Checked)
                    return false;
            }

            return true;
        }

        private function _CacheGet($Call)
        {

        }

        private function _CacheSet($Call, $Value)
        {

        }
    }

