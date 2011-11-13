<?php

    /*
     * @author BreathLess
     * @description: F Class
     * @package Codeine Framework
     * @subpackage Core
     * @version 6.0
     */

    define('Codeine', __DIR__);

    final class F
    {
        protected static $_Options;
        protected static $_Functions;
        protected static $_Namespace = 'Codeine';
        protected static $_Storage;
        protected static $_History = array();
        /**
             * @var SplStack;
             */
        protected static $_Stack;

        public static function Bootstrap ($Call = null)
        {
            self::$_Stack = new SplStack();
            self::$_Stack->push('Core');

            if (isset($Call['Path']))
            {
                if (is_array($Call['Path']))
                    self::$_Options['Path'][] = Codeine;
                else
                    self::$_Options['Path'] = array($Call['Path'], Codeine);
            }
            else
                self::$_Options['Path'][] = Codeine;

            self::_loadOptions();
        }

        public static function Merge($First, $Second)
        {
            if (is_array($Second) && is_array($First))
            {
                foreach ($Second as $Key => $Value)
                    if (isset($First[$Key]) && is_array($Value))
                        $First[$Key] = self::Merge($First[$Key], $Second[$Key]);
                    else
                        $First[$Key] = $Value;
            }
            else
                $First = $Second;

            return $First;
        }

        public static function findFile($Names)
        {
           $Names = (array) $Names;

           foreach ($Names as $Name)
               foreach (self::$_Options['Path'] as $ic => $Path)
                   if (file_exists($Filenames[$ic] = $Path.'/'.$Name))
                        return $Filenames[$ic];

           //trigger_error($Names[0].' not found'); // FIXME
           return null;
        }

        protected static function _loadSource($Call)
        {
            $Path = strtr($Call['_N'], '.', '/');

            if (isset($Call['Filename']))
                $Filename = $Call['Filename'];
            else
                $Filename = self::findFile(self::$_Options['Codeine']['Driver']['Path'].'/'.$Path.self::$_Options['Codeine']['Driver']['Extension']);

            if ($Filename)
                return (include $Filename);
            else
                return null;
        }

        /**
             * @description Проверяет, является ли массив правильно сконструированным вызовом.
             * @param  $Call
             * @return bool
             */
        public static function isCall($Call)
        {
            return (is_array($Call) && isset($Call['_N']));
        }

        public static function hashCall($Call)
        {
            if (self::isCall($Call))
                return $Call['_N'].':'.$Call['_F'].'('.sha1(serialize($Call)).')';
            else
                return serialize($Call);
        }

        /**
             * @description Выполняет вызов
             * @param  $Call
             * @return mixed
             */
        public static function Run($Call)
        {
            // Automerge Calls
            if (func_num_args() > 1)
            {
                $Calls = func_get_args();
                $Call = array_shift($Calls);

                $szCalls = count($Calls);
                for ($i = 0; $i<$szCalls; $i++)
                    $Call = F::Merge($Call, $Calls[$i]);

                return self::Run($Call);
            } // TODO Remove to driver

            if (self::isCall($Call))
            {
                $ParentNamespace = self::$_Namespace;
                self::$_Namespace = $Call['_N'];

                $Call = self::Merge(self::_loadOptions(), $Call);

                if(!isset($Call['NoBehaviours']))
                    foreach (self::$_Options['Codeine']['Behaviours'] as $Behaviour)
                        if (!(is_array($Call) && isset($Call['No'.$Behaviour])))
                            $Call = self::Run(
                                array(
                                    '_N' => 'Code.Behaviour.'.$Behaviour,
                                    '_F' => 'beforeRun',
                                    'Value' => $Call,
                                    'NoBehaviours' => true
                                ));

                if (!isset($Call['_F']))
                    $Call['_F'] = 'Do';

                self::$_Stack->push($Call);

                    if (!isset($Call['Result']))
                    {
                        if (null === self::Fn($Call['_F']))
                            if (null === self::_loadSource($Call))
                                $Result = isset($Call['Fallback'])? $Call['Fallback']: null;

                        $F = self::Fn($Call['_F']);

                        if (is_callable($F))
                            $Result = $F($Call);
                        else
                            $Result = isset($Call['Fallback'])? $Call['Fallback']: null;

                        if(!isset($Call['NoBehaviours']))
                            foreach (self::$_Options['Codeine']['Behaviours'] as $Behaviour)
                                if (!(is_array($Call) && isset($Call['No'.$Behaviour])))
                                    $Call = self::Run(
                                        array(
                                            '_N' => 'Code.Behaviour.'.$Behaviour,
                                            '_F' => 'afterRun',
                                            'Value' => $Call,
                                            'NoBehaviours' => true
                                        ));
                    }
                    else
                        $Result =  $Call['Result'];

                self::$_Stack->pop();

                self::$_Namespace = $ParentNamespace;

                return $Result;
            }
            else
            {
                d(__FILE__, __LINE__, $Call);
                trigger_error('Invalid call');
                return null;
            }
        }

        public static function Fn($Function, $Code = null)
        {
            if (null !== $Code)
            {
                if (false !== $Code)
                    self::$_Functions[self::$_Namespace][$Function] = $Code;
                else
                    unset(self::$_Functions[self::$_Namespace][$Function]);
            }
            else
            {
                if (isset(self::$_Functions[self::$_Namespace][$Function]))
                    return self::$_Functions[self::$_Namespace][$Function];
                else
                    return null;
            }

            // Fuckup of IDE hinting
            return function () {};
        }

        public static function Set($Key, $Value)
        {
            return self::$_Storage[self::$_Namespace][$Key] = $Value;
        }

        public static function Get($Key = null, $Default = null)
        {
            if (null === $Key && isset(self::$_Storage[self::$_Namespace]))
                return self::$_Storage[self::$_Namespace];

            if (isset(self::$_Storage[self::$_Namespace][$Key]))
                return self::$_Storage[self::$_Namespace][$Key];
            else
                return $Default;
        }

        public static function ifCall($Call)
        {
            if (self::isCall($Call))
                return F::Run($Call);
            else
                return $Call;
        }

        public static function Shutdown()
        {
            return null; // TODO onShutdown
        }

        public static function Error($errno , $errstr , $errfile , $errline , $errcontext)
        {
            // FIXME
            echo '<div>PHP: '.$errstr.' in <a href="xdebug://'.$errfile.'@'.$errline.'">'.$errfile.':'.$errline.'</a> </div>';
            d(__FILE__, __LINE__, self::$_Stack->top());
        }

        protected static function _loadOptions()
        {
            if (!isset(self::$_Options[self::$_Namespace]))
            {
                $Options = array();
                foreach (self::$_Options['Path'] as $Path)
                {
                    $Filename = self::findFile('Options/'.strtr(self::$_Namespace, '.','/').'.json');

                    if ($Filename)
                    {
                        $Options = json_decode(file_get_contents($Filename), true);
                        break;
                    }
                }

                if (empty($Options))
                    $Options = null;

                self::$_Options[self::$_Namespace] = $Options;
            }

            return self::$_Options[self::$_Namespace];
        }

        public static function Dump($File, $Line, $Call)
        {
            echo '<strong>File:'.$File.' line:'.$Line.'</strong>';
            if (is_array($Call))
                krsort($Call);
            var_dump($Call);
        }
    }

    function f()
    {
        return call_user_func_array(array('F','Run'), func_get_args());
    }

    function d()
    {
        return call_user_func_array(array('F','Dump'), func_get_args());
    }

    register_shutdown_function('F::Shutdown');
    set_error_handler('F::Error'); // Instability