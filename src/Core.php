<?php

    /*
     * @author BreathLess
     * @description: F Class
     * @package Codeine Framework
     * @subpackage Core
     * @version 7.2
     */

    define('Codeine', __DIR__);

    final class F
    {
        protected static $_Environment = 'Production';
        protected static $_Options;
        protected static $_Code;
        protected static $_Service = 'Codeine';
        protected static $_Method = 'Do';
        protected static $_Storage = array();
        protected static $_Speed;

        public static function Bootstrap ($Call = null)
        {
            self::$_Speed = array(microtime(true), memory_get_usage(), 0);

            mb_internal_encoding('UTF-8');
            setlocale(LC_ALL, "ru_RU.UTF-8");

            if (isset($_SERVER['Environment']))
                self::$_Environment = $_SERVER['Environment'];

            if (isset($Call['Environment']))
                self::$_Environment = $Call['Environment'];

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
            set_error_handler ('F::Error'); // Instability
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
                return (include_once $Filename);
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
            self::$_Speed[2]++;

            // TODO Infinite cycle protection

            if (($sz = func_num_args())>3)
            {
                for($ic = 3; $ic<$sz; $ic++)
                    if (is_array($Argument = func_get_arg ($ic)))
                        $Call = F::Merge($Call, $Argument);
            }

            if ($Service !== null)
                self::$_Service = $Service;

            if ($Method !== null)
                self::$_Method  = $Method;

            $Result = F::Execute(self::$_Service, self::$_Method, $Call);

            return $Result;
        }

        public static function Execute($Service, $Method, $Call)
        {
            $Call = self::Merge(self::loadOptions(), $Call);

            if ((null === self::getFn($Method)) && (null === self::_loadSource($Service)))
            {
                $Result = (is_array($Call) && isset($Call['Fallback']))?
                           $Call['Fallback']                        :
                           F::Run('Code.Flow.Hook', 'Run', self::$_Options['Codeine'], array ('On'   => 'Service.NotFound',
                                                                                       'Call' => $Call));
            }
            else
            {
                $F = self::getFn($Method);

                if (is_callable($F))
                    $Result = $F($Call);
                else
                    $Result = isset($Call['Fallback']) ? $Call['Fallback'] : null;
            }

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

        public static function Live($Variable, $Call = array())
        {
            if (self::isCall($Variable))
            {
                if (($sz = func_num_args())>2)
                {
                    for($ic = 2; $ic<$sz; $ic++)
                        if (is_array($Argument = func_get_arg ($ic)))
                            $Call = F::Merge($Call, $Argument);
                }

                return F::Run($Variable['Service'], $Variable['Method'], $Call, isset($Variable['Call'])? $Variable['Call']: array());
            }
            else
            {
                if (is_array($Variable))
                    foreach ($Variable as &$cVariable)
                        $cVariable = self::Live($cVariable, $Call);
                else
                {
                    if ('$' == substr($Variable, 0, 1))
                        $Variable = F::Dot($Call, substr($Variable, 1));
                }

                return $Variable;
            }
        }

        public static function Shutdown()
        {
            F::Run('Code.Run.Delayed', 'Flush');
            F::Run('IO', 'Close', array('Storage' => 'Developer'));
            return null; // TODO onShutdown
        }

        public static function Extract($Array, $Keys)
        {
            $Data = array();
            foreach ($Keys as $Key)
                if (is_scalar($Key) &&  isset($Array[$Key]))
                    $Data[$Key] = $Array[$Key];

            return $Data;
        }

        public static function Map ($Array, $Fn, $Data = null, $FullKey = '')
        {
            if (is_array ($Array))
                foreach ($Array as $Key => &$Value)
                {
                    $NewFullKey = is_numeric($Key)? $FullKey.'#': $FullKey.'.'.$Key;

                    $Fn($Key, $Value, $Data, $NewFullKey);

                    if (is_array ($Value))
                        self::Map ($Value, $Fn, &$Data, $NewFullKey);
                }

            return true;
        }

        public static function Dot ($Array, $Key)
        {
            if (strpos($Key, '.') !== false)
            {
                $Keys = explode('.', $Key);

                $Tail = $Array;
                foreach ($Keys as $iKey)
                    if (isset($Tail[$iKey]))
                        $Tail = $Tail[$iKey];
                    else
                        return null;

                return $Tail;
            }
            else
                return isset($Array[$Key])? $Array[$Key]: null;
        }

        public static function Error($errno , $errstr , $errfile , $errline , $errcontext)
        {
            return F::Run(
                'IO', 'Write',
                array(
                     'Storage' => 'Developer',
                     'Type' => 'Error',
                     'Data' => $errstr.' '.$errfile.'@'.$errline
                )
            );
        }

        public static function Log ($Message, $Type = 'Info', $Tags = '')
        {
            return F::Run(
                'IO', 'Write',
                array(
                     'Storage' => 'Developer',
                     'Type' => $Type,
                     'Data' => $Message,
                     'Tags' => $Tags
                )
            );
        }

        public static function loadOptions($Service = null)
        {
            $Service = ($Service == null)? self::$_Service: $Service;

            if (!isset(self::$_Options[$Service]))
            {
                $Options = array();

                    if ($Filenames = self::findFiles (
                        array(
                             'Options/'.strtr($Service, '.', '/').'.'.self::$_Environment.'.json',
                             'Options/'.strtr($Service, '.', '/').'.json'
                        )
                    ))
                    {
                        $Options = array();

                        foreach ($Filenames as $Filename)
                        {
                            $Current = json_decode(file_get_contents($Filename), true);

                            if ($Filename && !$Current)
                            {
                                trigger_error('JSON: ' . $Filename.':'. json_last_error()); //FIXME
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

        public static function Set ($Key, $Value)
        {
            return self::$_Storage[$Key] = $Value;
        }

        public static function Get ($Key)
        {
            return isset(self::$_Storage[$Key]) ? self::$_Storage[$Key]: null;
        }

        public static function Speed()
        {
            $Time = (microtime(true)-self::$_Speed[0]);
            $Memory = memory_get_peak_usage()-self::$_Speed[1];
            return 'SI:'.(round(1/(($Memory/1048576)*$Time), 2)).'('.self::$_Speed[2].')'; // megabyte-second
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