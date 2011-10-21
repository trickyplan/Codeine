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
        protected static $_Namespace;
        protected static $_Storage;
        protected static $_History = array();
        /**
         * @var SplStack;
         */
        protected static $_Stack;

        public static function Bootstrap ($Options = null)
        {
            self::$_Stack = new SplStack();

            self::$_Options = $Options;

            if (isset($Options['Path']))
            {
                if (is_array($Options['Path']))
                    self::$_Options['Path'][] = Codeine;
                else
                    self::$_Options['Path'] = array($Options['Path'], Codeine);
            }
            else
                self::$_Options['Path'][] = Codeine;

            self::$_Options = F::Merge(self::$_Options, self::Options('Codeine'));
        }

        public static function Merge($First, $Second, $Third = false)
        {
            if (is_array($Second))
               foreach ($Second as $Key => $Value)
                   if (is_array($First) && array_key_exists($Key, $First) && is_array($Value))
                       $First[$Key] = self::Merge($First[$Key], $Second[$Key]);
                   else
                       $First[$Key] = $Value;
            else
               return $Second;

            // No more then three levels
            if ($Third)
               $First = self::Merge($First, $Third);

            return $First;
        }

        public static function Find($Names)
        {
           if (!is_array($Names))
                $Names = array($Names);

           foreach ($Names as $Name)
               foreach (self::$_Options['Path'] as $ic => $Path)
                    if (file_exists($Filenames[$ic] = $Path.'/'.$Name))
                        return $Filenames[$ic];

           trigger_error($Names[0].' not found'); // FIXME
           return null;
        }

        protected static function _loadSource($Call)
        {
            $Path = strtr($Call['_N'], array('.' => '/'));

            if (isset($Call['Filename']))
                $Filename = $Call['Filename'];
            else
                $Filename = self::Find(self::Options('Codeine', 'Driver.Path').'/'.$Path.self::Options('Codeine', 'Driver.Extension'));

            if (file_exists($Filename))
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
                return sha1(serialize($Call));
        }

        /**
         * @description Выполняет вызов
         * @param  $Call
         * @return void
         */
        public static function Run($Call)
        {
            $ParentNamespace = self::$_Namespace;

            // Automerge Calls
            if (func_num_args() > 1)
            {
                $Calls = func_get_args();
                $Call = array_shift($Calls);

                $szCalls = count($Calls);
                for ($i = 0; $i<$szCalls; $i++)
                    $Call = F::Merge($Call, $Calls[$i]);

                return F::Run($Call);
            }

            $Behaviours = F::Options('Codeine', 'Behaviours');

            if(!(is_array($Call) && isset($Call['NoBehaviours'])))
                foreach ($Behaviours as $Behaviour)
                    if (!(is_array($Call) && isset($Call['No'.$Behaviour])))
                        $Call = self::Run(
                            array(
                                '_N' => 'Code.Behaviour.'.$Behaviour,
                                '_F' => 'beforeRun',
                                'Value' => $Call,
                                'NoBehaviours' => true
                            ));

            if (null === $Call)
                return null;

            if (!isset($Call['_F']))
                $Call['_F'] = 'Do';

                self::$_Stack->push($Call);

                    if (!isset($Call['Result']))
                    {
                        self::$_Namespace = $Call['_N'];

                        if (null === self::Fn($Call['_F']))
                            if (null === self::_loadSource($Call))
                                return isset($Call['Fallback'])? $Call['Fallback']: null;

                        $F = self::Fn($Call['_F']);

                        if (is_callable($F))
                            $Result = $F($Call);
                        else
                            return isset($Call['Fallback'])? $Call['Fallback']: null;

                        if(!(is_array($Call) && isset($Call['NoBehaviours'])))
                            foreach ($Behaviours as $Behaviour)
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
                        return $Call['Result'];

                self::$_Stack->pop();

            self::$_Namespace = $ParentNamespace;
            return $Result;
        }

        public static function Fn($Function, $Code = null)
        {
            if ($Function instanceof Traversable || is_array($Function))
                foreach ($Function as $cFn)
                    self::Fn($cFn, $Code);
            else
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

        public function ifCall($Call)
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
            $source = file($errfile);

            return F::Run(
                array(
                    'Send' => 'Event',
                    'Message' => 'PHP: '.$errstr.' in <a href="ide://'.$errfile.'@'.$errline.'">'.$errfile.':'.$errline.'</a> <br/>',
                    'Call' => self::$_Stack->top()
                )
            );
        }

        public static function Options($Scope, $Key = null)
        {
            if (!isset(self::$_Options[$Scope]))
            {
                $Options = array();

                foreach (self::$_Options['Path'] as $Path)
                {
                    $Filename = $Path.'/Options/'.strtr($Scope, '.', '/').'.json';
                    
                    if (file_exists($Filename))
                        $Options = F::Merge($Options, json_decode(file_get_contents($Filename), true));
                }
                
                self::$_Options[$Scope] = $Options;
            }

            if (null !== $Key)
            {
                if (isset(self::$_Options[$Scope][$Key]))
                    return self::$_Options[$Scope][$Key];
                else
                    if (mb_strpos($Key, '.') !== false)
                    {
                        $Keys = explode('.', $Key);

                        $Value = self::$_Options[$Scope];

                        foreach ($Keys as $Key)
                            if (isset($Value[$Key]))
                                $Value = $Value[$Key];
                            else
                                return null;

                        return $Value;
                    }

               return null;
            }
            else
                return self::$_Options[$Scope];

            return array(); // Fuck IDE Hinting
        }

        public static function DUMP($File, $Line, $Call)
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
        return call_user_func_array(array('F','DUMP'), func_get_args());
    }

    register_shutdown_function('F::Shutdown');
    set_error_handler('F::Error');