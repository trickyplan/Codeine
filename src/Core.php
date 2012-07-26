<?php

    /*
     * @author BreathLess
     * @description: F Class
     * @package Codeine Framework
     * @subpackage Core
     * @version 7.4.5
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
        protected static $_Profile = array();
        protected static $_Log = array();

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
            self::loadOptions('Project');

            if (isset(self::$_Options['Project']['Version']['Codeine']) && self::$_Options['Project']['Version']['Codeine'] > self::$_Options['Codeine']['Version'])
                die('Codeine '.self::$_Options['Project']['Version']['Codeine'].'+ needed. Installed: '.self::$_Options['Codeine']['Version']);

            register_shutdown_function ('F::Shutdown');
            set_error_handler ('F::Error'); // Instability
        }

        public static function Merge($First, $Second)
        {
            if (is_array($Second))
            {
                if (is_array($First))
                    {
                        foreach ($Second as $Key => $Value)
                            if (isset($First[$Key]) && is_array($Value))
                                $First[$Key] = self::Merge($First[$Key], $Second[$Key]);
                            else
                                $First[$Key] = $Value;
                    }
                else
                    $First = $Second;
            }

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

            if (empty($Results))
                return null;
            else
                return $Results;
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
                F::Log($Service.' not found');
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
                return sha1(serialize($Call));
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

            if (isset($Call['Behaviours'][$Method]))
            {
                $Behaviours = $Call['Behaviours'][$Method];
                unset($Call['Behaviours']);

                foreach ($Behaviours as $Behaviour)
                {
                    $Result = F::Run('Code.Behaviours.'.$Behaviour, 'Run', array(
                                                                      'Service' => $Service,
                                                                      'Method' => $Method,
                                                                      'Call' => $Call
                                                                 )); // FIXME Many behaviours
                }
            }
            else
                $Result = F::Execute($Service, $Method, $Call);



            return $Result;
        }

        public static function Execute($Service, $Method, $Call)
        {
            self::$_Speed[2]++;

            $OldService = self::$_Service;
            $OldMethod = self::$_Method;

            if ($Service !== null)
                self::$_Service = $Service;

            if ($Method !== null)
                self::$_Method  = $Method;

            $Call = self::Merge(self::loadOptions(), $Call);

            if ((null === self::getFn($Method)) && (null === self::_loadSource($Service)))
                $Result = (is_array($Call) && isset($Call['Fallback']))? $Call['Fallback'] : null;
            else
            {
                $F = self::getFn($Method);

                // HARDCORE PROFILING
                //$ST  = microtime(true);

                if (is_callable($F))
                    $Result = $F($Call);
                else
                    $Result = isset($Call['Fallback']) ? $Call['Fallback'] : null;


                //if (!isset(self::$_Profile[self::$_Service.':'.self::$_Method]))
                //    self::$_Profile[self::$_Service.':'.self::$_Method] = 0;

                //self::$_Profile[self::$_Service.':'.self::$_Method] += round((microtime(true) - $ST)*1000);
            }

            self::$_Service = $OldService;
            self::$_Method = $OldMethod;

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
            return function (){};
        }

        public static function Live($Variable, $Call = array())
        {
            if (isset($Variable['NoLive']))
                return $Variable;

            if (self::isCall($Variable))
            {
                if (($sz = func_num_args())>2)
                {
                    for($ic = 2; $ic<$sz; $ic++)
                        if (is_array($Argument = func_get_arg ($ic)))
                            $Call = F::Merge($Call, $Argument);
                }

                return F::Run($Variable['Service'], $Variable['Method'],
                    $Call, isset($Variable['Call'])? $Variable['Call']: array());
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
            // HARDCORE PROFILING
            // arsort(self::$_Profile);
            // d(__FILE__, __LINE__, self::$_Profile);

            F::Run('Code.Run.Delayed', 'Flush');

            if (self::$_Environment != 'Production')
            {
                F::Execute(
                    'IO', 'Write',
                    array(
                        'Storage' => 'Developer',
                        'Data' => self::$_Log
                    )
                );

                F::Run('IO', 'Close', array('Storage' => 'Developer'));
            }


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

        public static function RunN($Variable, $Key, $Call = array())
        {

            if (null !== $Variable)
            {
                if (is_array($Variable))
                    foreach ($Variable as &$cVariable)
                        $cVariable = F::Run($Call['Service'], $Call['Method'], $Call['Call'], array($Key => $cVariable));
                else
                    $Variable = F::Run($Call['Service'], $Call['Method'], $Call['Call'], array($Key => $Variable));
            }
            else
                $Variable = F::Run($Call['Service'], $Call['Method'], $Call['Call']);

            return $Variable;
        }

        public static function Map ($Array, $Fn, $Data = null, $FullKey = '')
        {
            if (is_array ($Array))
                foreach ($Array as $Key => &$Value)
                {
                    $NewFullKey = is_numeric($Key)? $FullKey.'#': $FullKey.'.'.$Key;

                    $Fn($Key, $Value, $Data, $NewFullKey, $Array);

                    if (is_array ($Value))
                        $Array[$Key] = self::Map ($Value, $Fn, $Data, $NewFullKey, $Array);
                    else
                        $Array[$Key] = $Value;
                }
            else
                $Array = $Fn('', $Array, $Data, $FullKey);

            return $Array;
        }

        public static function Dot ($Array, $Key)
        {
            if (func_num_args() == 3)
            {
                $Value = func_get_arg(2);

                if (strpos($Key, '.') !== false)
                {
                    $Keys = explode('.', $Key);
                    $Key = array_shift($Keys);

                    if (!isset($Array[$Key]))
                        $Array[$Key] = [];

                    $Array[$Key] = F::Dot($Array[$Key], implode('.', $Keys), $Value);
                }
                else
                    $Array[$Key] = $Value;

                return $Array;
            }

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

        public static function Hook($On, $Call)
        {
            if (isset($Call['Hooks']))
                 if ($Hooks = F::Dot($Call, 'Hooks.' . $On))
                     foreach ($Hooks as $Hook)
                     {
/*                         if(isset($Hook['Call']))
                            $Hook['Call'] = F::Map($Hook['Call'], function ($Key, &$Value) use ($Call)
                            {
                                if (is_scalar($Value) && substr($Value, 0, 1) == '$')
                                    $Value = F::Dot($Call, substr($Value, 1));
                            });*/

                         $Call = F::Live($Hook, $Call);
                     }

            return $Call;
        }

        public static function Error($errno , $errstr , $errfile , $errline , $errcontext)
        {
            if (isset(self::$_Options['Project']['Airbrake']))
            {
                $Airbrake = curl_init('http://one2tool.local/airbrake');
                curl_setopt_array($Airbrake, [
                    CURLOPT_POST => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POSTFIELDS => json_encode(['Report' => ['Message' => $errstr]])]);
                curl_exec($Airbrake);
            }
            return F::Log($errstr.' '.$errfile.'@'.$errline, 'Error');
        }

        public static function Log ($Message, $Type = 'Info')
        {
            return self::$_Log[] = array(round(microtime(true) - self::$_Speed[0], 4), $Message, $Type);
        }

        public static function loadOptions($Service = null, $Method = null)
        {
            $Service = ($Service == null)? self::$_Service: $Service;
            $Method = ($Method == null)? self::$_Method: $Method;

            // Если контракт уже не загружен
            if (!isset(self::$_Options[$Service][$Method]))
            {
                $Options = array();

                    $ServicePath = strtr($Service, '.', '/');

                    if ($Filenames = self::findFiles (
                        array(
                             'Options/'.$ServicePath.'.'.self::$_Environment.'.json',
                             'Options/'.$ServicePath.'.json',
                             'Options/'.$ServicePath.'/'.$Method.'.'.self::$_Environment.'.json',
                             'Options/'.$ServicePath.'/'.$Method.'.json',
                        )
                    ))
                    {
                        $Options = array();

                        foreach ($Filenames as $Filename)
                        {
                            $Current = json_decode(file_get_contents($Filename), true);

                            if ($Filename && !$Current)
                            {
                                switch (json_last_error()) {
                                    case JSON_ERROR_NONE:
                                        $JSONError =  ' - No errors';
                                    break;
                                    case JSON_ERROR_DEPTH:
                                        $JSONError =  ' - Maximum stack depth exceeded';
                                    break;
                                    case JSON_ERROR_STATE_MISMATCH:
                                        $JSONError =  ' - Underflow or the modes mismatch';
                                    break;
                                    case JSON_ERROR_CTRL_CHAR:
                                        $JSONError =  ' - Unexpected control character found';
                                    break;
                                    case JSON_ERROR_SYNTAX:
                                        $JSONError =  ' - Syntax error, malformed JSON';
                                    break;
                                    case JSON_ERROR_UTF8:
                                        $JSONError =  ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                                    break;
                                    default:
                                        $JSONError =  ' - Unknown error';
                                    break;
                                }
                                trigger_error('JSON Error: ' . $Filename.':'. $JSONError); //FIXME
                                return null;
                            }

                            $Options = self::Merge($Options, $Current);
                        }
                    }

                    if (isset($Options['Mixins']))
                        foreach($Options['Mixins'] as $Mixin)
                            $Options = F::Merge($Options, F::loadOptions($Mixin));

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
            return (isset(self::$_Storage[$Key]) ? self::$_Storage[$Key]: null);
        }

        public static function Speed()
        {
            $Time = (microtime(true)-self::$_Speed[0]);
            $Memory = memory_get_peak_usage(true)-self::$_Speed[1];
            return 'SI:'.(round(1/(($Memory/1048576)*$Time), 2)).'('.self::$_Speed[2].')'.round($Memory/1048576,2); // megabyte-second
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