<?php

    /*
     * @author BreathLess
     * @description: F Class
     * @package Codeine Framework
     * @subpackage Core
     * @version 7.1
     */

    define('Codeine', __DIR__);

    final class F
    {
        protected static $_Environment = 'Production';
        protected static $_Options;
        protected static $_Code;
        protected static $_Service = 'Codeine';
        protected static $_Method = 'Do';
        protected static $_Storage;
        protected static $_History = array();

        /**
             * @var SplStack;
             */
        protected static $_Stack;

        public static function Bootstrap ($Call = null)
        {
            mb_internal_encoding('UTF-8');
            if (isset($_SERVER['Environment']))
                self::$_Environment = $_SERVER['Environment'];

            if (isset($Call['Environment']))
                self::$_Environment = $Call['Environment'];

            self::$_Stack = new SplStack();
            self::$_Stack->push('Core');

            if (isset($Call['Path']))
            {
                if (is_array($Call['Path']))
                    self::$_Options['Path'] = array_merge($Call['Path'], array(Codeine));
                else
                    self::$_Options['Path'] = array($Call['Path'], Codeine);
            }
            else
                self::$_Options['Path'] = array (Codeine);

            self::loadOptions();

            register_shutdown_function ('F::Shutdown');
            // set_error_handler ('F::Error'); // Instability
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

        public static function findFiles ($Names)
        {
            $Results = array();

            $Names = (array) $Names;

            foreach (self::$_Options['Path'] as $ic => $Path)
                foreach ($Names as $Name)
                    if (file_exists($Filenames[$ic] = $Path . '/' . $Name))
                        $Results[] = $Filenames[$ic];

            $Results = array_reverse($Results);
            return empty($Results)? null: $Results;
        }

        protected static function _loadSource($Service)
        {
            $Path = strtr($Service, '.', '/');

            if (isset($Call['Filename']))
                $Filename = $Call['Filename'];
            else
                $Filename = self::findFile(self::$_Options['Codeine']['Driver']['Path'].'/'.$Path.self::$_Options['Codeine']['Driver']['Extension']);

            if ($Filename)
                return (include $Filename);
            else
            {
                return null;
            }
        }

        /**
         * @description Проверяет, является ли массив правильно сконструированным вызовом.
         * @param  $Call
         * @return bool
         */
        public static function isCall($Call)
        {
            return (is_array($Call) && isset($Call['Service']) && isset($Call['Method']));
        }

        public static function hashCall($Call)
        {
            if (self::isCall($Call))
                return $Call['Service'].':'.$Call['Method'].'('.sha1(serialize($Call)).')';
            else
                return serialize($Call);
        }

        /**
         * @description Выполняет вызов
         * @param  $Call
         * @return mixed
         */
        public static function Run($Service, $Method, $Call = array())
        {
            // TODO Infinite cycle protection

            if (($sz = func_num_args())>3)
            {
                for($ic = 3; $ic<$sz; $ic++)
                    if (is_array($Argument = func_get_arg ($ic)))
                        $Call = F::Merge($Call, $Argument);
            }

            $ParentNamespace = self::$_Service;

            self::$_Service = $Service;
            self::$_Method  = $Method;

            $Call = self::Merge(self::loadOptions(), $Call);

            self::$_Stack->push($Call);

            if (!isset($Call['Result']))
            {
                if ((null === self::getFn(self::$_Method)) && (null === self::_loadSource (self::$_Service)))
                    $Result = (is_array($Call) && isset($Call['Fallback']))? $Call['Fallback']: null;
                else
                    {
                        $F = self::getFn($Method);

                        if (is_callable($F))
                           $Result = $F($Call);
                        else
                           $Result = isset($Call['Fallback'])? $Call['Fallback']: null;
                    }
            }
            else
            {
                if(is_array ($Call))
                    $Result =  $Call['Result'];
                else
                    $Result = null;
            }

            self::$_Stack->pop();

            self::$_Service = $ParentNamespace;

            return $Result;
        }

        public static function setFn($Function, $Code)
        {
            return self::$_Code[self::$_Service.'.'.$Function] = $Code;
        }

        public static function getFn($Function)
        {
            if (isset(self::$_Code[self::$_Service . '.' . $Function]))
                return self::$_Code[self::$_Service . '.' . $Function];
            else
                return null;

            // Fuckup of IDE hinting
            return function ()
            { };
        }

        public static function Set($Key, $Value)
        {
            return self::$_Storage[self::$_Service][$Key] = $Value;
        }

        public static function Get($Key = null, $Default = null)
        {
            if (null === $Key && isset(self::$_Storage[self::$_Service]))
                return self::$_Storage[self::$_Service];

            if (isset(self::$_Storage[self::$_Service][$Key]))
                return self::$_Storage[self::$_Service][$Key];
            else
                return $Default;
        }

        public static function Live($Variable, $Call = array())
        {
            if (self::isCall($Variable))
                return F::Run($Variable['Service'], $Variable['Method'], $Call, isset($Variable['Call'])? $Variable['Call']: array());
            else
                return $Variable;
        }

        public static function Shutdown()
        {
            /*file_put_contents(Root.'/Data/graph.png', F::Run (
                        array(
                             'Service' => 'View.Generators.Graphviz',
                             'Method' => 'Do',
                             'Graphviz.Layout' => 'dot',
                             'Title' => 'Calls',
                             'Value' => self::$_History,
                             'Format' => 'png'
                        )
                    ));*/

            return null; // TODO onShutdown
        }

        public static function Map ($Array, $Fn, $Data = null, $FullKey = '')
        {
            if (is_array ($Array))
                foreach ($Array as $Key => &$Value)
                {
                    $NewFullKey = is_numeric($Key)? $FullKey.'#': $FullKey.'.'.$Key;

                    $Fn($Key, $Value, &$Data, $NewFullKey);

                    if (is_array ($Value))
                        self::Map (&$Value, $Fn, &$Data, $NewFullKey);
                }

            return true;
        }

        public static function Error($errno , $errstr , $errfile , $errline , $errcontext)
        {
            // FIXME
            echo '<div class="Error"> PHP: '.$errstr.' in <a href="xdebug://'.$errfile.'@'.$errline.'">'.$errfile.':'.$errline.'</a> </div>';
            d(__FILE__, __LINE__, self::$_Stack->top());
        }

        public static function loadOptions($Service = null, $Method = null)
        {
            $Service = ($Service == null)? self::$_Service: $Service;
            $Method = ($Method  == null) ? self::$_Method : $Method;

            if (!isset(self::$_Options[$Service]))
            {
                $Options = array();

                    if ($Filenames = self::findFiles (
                        array(
                             'Options/'.strtr($Service, '.', '/').'.'.self::$_Environment.'.json',
                             'Options/'.strtr($Service, '.', '/').'.json',
                             'Options/'.strtr($Service, '.', '/').'/'.$Method.'.'.self::$_Environment.'.json',
                             'Options/'.strtr($Service, '.', '/').'/'.$Method.'.json')
                        )
                    )
                    {
                        $Options = array();

                        foreach ($Filenames as $Filename)
                        {
                            $Current = json_decode(file_get_contents($Filename), true);

                            if ($Filename && !$Current)
                            {
                                trigger_error('JSON file corrupted: ' . $Filename); //FIXME
                                return null;
                            }

                            $Options = self::Merge($Options, $Current);
                        }
                    }

                self::$_Options[$Service] = $Options;
            }

            return self::$_Options[$Service];
        }

        public static function Dump($File, $Line, $Call)
        {
            // FIXME!
            echo '<div class="xdebug-header">'.substr($File, strpos($File, 'Drivers')).' <strong>@'.$Line.'</strong></div>';

            var_dump($Call);

            return $Call;
        }

        public static function getOption($Key)
        {
            return isset(self::$_Options[$Key])? self::$_Options[$Key]: null;
        }
    }

    function f()
    {
        return call_user_func_array(array('F','Run'), func_get_args());
    }

    function d()
    {
        call_user_func_array(array('F','Dump'), func_get_args());
        return func_get_arg(2);
    }