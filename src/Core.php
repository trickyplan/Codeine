<?php

    /*
     * @author BreathLess
     * @description: F Class
     * @package Codeine Framework
     * @subpackage Core
          */

    define('Codeine', __DIR__);

    final class F
    {
        private static $_Environment = 'Production';
        private static $_Options;
        private static $_Code;

        private static $_Service = 'Codeine';
        private static $_Method = 'Do';

        private static $_Storage = array();
        private static $_Ticks = array();
        private static $_Speed = array();
        private static $_Counters = array();
        private static $_Log = array();

        private static $_Live = false;

        public static function Environment()
        {
            return self::$_Environment;
        }

        public static function Bootstrap ($Call = null)
        {
            self::$_Live = true;

            self::Start(self::$_Service . '.' . self::$_Method);

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

            self::Log('Codeine: '.self::$_Options['Codeine']['Version']);
            self::Log('Environment: '.self::$_Environment);

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
                   if (F::file_exists($Filenames[$ic] = $Path.'/'.$Name))
                        return $Filenames[$ic];

           return null;
        }

        public static function findFiles ($Names)
        {
            $Results = array();

            $Names = (array) $Names;

            foreach (self::$_Options['Path'] as $ic => $Path)
                foreach ($Names as $Name)
                    if (F::file_exists($Filenames[$ic] = $Path . '/' . $Name))
                        $Results[] = $Filenames[$ic];

            $Results = array_reverse($Results);

            if (empty($Results))
                return null;
            else
                return $Results;
        }

        private static function _loadSource($Service)
        {
            $Path = strtr($Service, '.', '/');

            $Filename = self::findFile(self::$_Options['Codeine']['Driver']['Path'].'/'.$Path.self::$_Options['Codeine']['Driver']['Extension']);

            if ($Filename)
                return (include $Filename);
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

        public static function Run($Service, $Method = null , $Call = array())
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
            self::Counter('Calls');

            $OldService = self::$_Service;
            $OldMethod = self::$_Method;

            if ($Service !== null)
                self::$_Service = $Service;

            if ($Method !== null)
                self::$_Method  = $Method;

            $Call = self::Merge(self::loadOptions(), $Call);

            if ((null === self::getFn(self::$_Method)) && (null === self::_loadSource(self::$_Service)))
                $Result = (is_array($Call) && isset($Call['Fallback']))? $Call['Fallback'] : null;
            else
            {
                $F = self::getFn(self::$_Method);

                if (is_callable($F))
                {
                    self::Stop($OldService. '.' . $OldMethod);
                    self::Start(self::$_Service . '.' . self::$_Method);
                    $Result = $F($Call);
                    self::Counter(self::$_Service);
                    self::Counter(self::$_Service.'.'.self::$_Method);
                    self::Stop(self::$_Service . '.' . self::$_Method);
                    self::Start($OldService. '.' . $OldMethod);
                }
                else
                    $Result = isset($Call['Fallback']) ? $Call['Fallback'] : null;

            }

            self::$_Service = $OldService;
            self::$_Method = $OldMethod;

            return $Result;
        }

        public static function setFn($Function, $Code = null)
        {
            return self::$_Code[self::$_Service][$Function] = $Code;
        }

        public static function getFn($Function)
        {
            if (isset(self::$_Code[self::$_Service][$Function]))
            {
                return self::$_Code[self::$_Service][$Function];
            }
            else
                return null;

            // Fuckup of IDE hinting
            return function (){};
        }

        public static function Live($Variable, $Call = array())
        {
            self::Counter('Live');

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

            self::Stop(self::$_Service . '.' . self::$_Method);

            if (isset($_REQUEST['SR71']))
                self::SR71();

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
                {
                    if ($Value === null)
                        unset($Array[$Key]);
                    else
                        $Array[$Key] = $Value;
                }

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
                    {
                        if (isset($Array[$Key]))
                            return $Array[$Key];
                        else
                            return null;
                    }

                return $Tail;
            }
            else
                return isset($Array[$Key])? $Array[$Key]: null;
        }

        public static function Hook($On, $Call)
        {
            if (isset($Call['Hooks']))
                 if ($Hooks = F::Dot($Call, 'Hooks.' . $On))
                 {
                     foreach ($Hooks as $Hook)
                         if (F::isCall($Hook))
                             $Call = F::Run($Hook['Service'],$Hook['Method'], isset($Hook['Call'])? $Hook['Call']: [],  $Call);
                         else
                             $Call = F::Merge($Call, $Hook);
                 }

            return $Call;
        }

        public static function Error($errno , $errstr , $errfile , $errline , $errcontext)
        {
            return F::Log($errstr.' '.$errfile.'@'.$errline, 'Error');
        }

        public static function Log ($Message, $Type = 'Info')
        {
            if (self::$_Environment !== 'Production')
                return self::$_Log[] = array(round(microtime(true) - self::$_Ticks['T']['Codeine.Do'], 4), $Message, $Type);
        }

        public static function loadOptions($Service = null, $Method = null)
        {
            $Service = ($Service == null)? self::$_Service: $Service;
            $Method = ($Method == null)? self::$_Method: $Method;

            // Если контракт уже не загружен
            if (!isset(self::$_Options[$Service]))
            {
                $Options = array();

                $ServicePath = strtr($Service, '.', '/');

                $Filenames = [];

                if (self::$_Environment != 'Production')
                    $Filenames[] = 'Options/'.$ServicePath.'.'.self::$_Environment.'.json';

                $Filenames[] = 'Options/'.$ServicePath.'.json';

                if ($Filenames = self::findFiles ($Filenames))
                {
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

        public static function file_exists($Filename)
        {
            return isset(self::$_Storage['FE'][$Filename])? self::$_Storage['FE'][$Filename]: self::$_Storage['FE'][$Filename] = file_exists($Filename);
        }

        public static function Counter ($Key, $Value = 1)
        {
            if (isset(self::$_Counters['C'][$Key]))
                self::$_Counters['C'][$Key]+= $Value;
            else
                self::$_Counters['C'][$Key] = $Value;

            return self::$_Counters['C'][$Key];
        }

        public static function Start ($Key)
        {
            return self::$_Ticks['T'][$Key] = microtime(true);
        }

        public static function Stop ($Key)
        {
            if (isset(self::$_Counters['T'][$Key]))
                return self::$_Counters['T'][$Key] += round((microtime(true) - self::$_Ticks['T'][$Key])*1000,2);
            else
                return self::$_Counters['T'][$Key] = round((microtime(true) - self::$_Ticks['T'][$Key])*1000,2);
        }

        public static function MStart ($Key)
        {
            return self::$_Ticks['M'][$Key] = memory_get_peak_usage(true);
        }

        public static function MStop ($Key)
        {
            return self::$_Counters['M'][$Key] += memory_get_peak_usage(true) - self::$_Ticks['M'][$Key];
        }

        public static function SR71()
        {
           $Summary['Time'] = array_sum(self::$_Counters['T']);
           $Summary['Calls'] = array_sum(self::$_Counters['C']);

           arsort(self::$_Counters['T']);
           echo "<pre>time\tcalls\trtime\trcall\tfn\n".$Summary['Time']."\n";
           foreach (self::$_Counters['T'] as $Key => $Value)
               echo $Value."\t".self::$_Counters['C'][$Key]."\t".round(($Value/$Summary['Time'])*100)."%\t".round((self::$_Counters['C'][$Key]/$Summary['Calls'])*100)."%\t".$Key."\n";

           echo '</pre>';
        }

        public static function setLive($Live)
        {
            self::$_Live = (bool) $Live;
        }

        public static function getLive()
        {
            return self::$_Live;
        }

        public static function Reload ()
        {
            foreach (self::$_Options as $Service)
                F::loadOptions($Service);

            foreach (self::$_Code as $Service)
                F::_loadSource($Service);

            return true;
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